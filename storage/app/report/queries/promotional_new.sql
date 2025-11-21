select 
	activity_id,
	act.request_no ,
	act.payment_status,
	act.gl_category,
	rc.name AS category,
	INITCAP(REPLACE(act.status, '_', ' ')) request_status,
	
	--COALESCE(ven.code, '') AS vendor,
	COALESCE(rb_adv.employee_id, '') AS name,
	COALESCE(rb.employee_id, '') AS requested_by,
	COALESCE(cb.employee_id, '') AS created_by,
	COALESCE(ub.employee_id, '') AS updated_by,
	
	
	SUM(adv_amount) ad_amount,
	SUM(sett_amount) sett_amount,
	COALESCE(SUM(sett_amount), 0) - SUM(adv_amount) AS balance
	from 
(
	select act.request_no, act.id activity_id, asl.request_id, SUM(adv.approved_expense) adv_amount, 0 sett_amount, adv.requested_by, act.payment_status, act.status
	from requests act
	left join advance_settlement_list asl ON act.id = asl.activity_id and asl.level = 1 
	left join requests adv ON asl.request_id = adv.id
	where act.id IN (select request_id from promotional_activities_list) 
	group by act.request_no,act.id,asl.request_id,adv.requested_by, act.payment_status, act.status
	
	UNION ALL
	
	select act.request_no,act.id activity_id,asl.request_id,0 adv_amount,SUM(adv.approved_expense) sett_amount, 0 requested_by, act.payment_status, act.status
	from requests act
	left join advance_settlement_list asl ON act.id = asl.activity_id and asl.level > 1 
	left join requests adv ON asl.request_id = adv.id
	where act.id IN (select request_id from promotional_activities_list) 
	group by act.request_no,act.id,asl.request_id, act.payment_status, act.status

) a

left join requests act  ON a.activity_id = act.id

	
LEFT JOIN request_categories AS rc ON act.request_category_id = rc.id
	
LEFT JOIN users AS rb_adv ON a.requested_by = rb_adv.id
	
LEFT JOIN users AS rb ON act.requested_by = rb.id
--LEFT JOIN vendors AS ven ON act.vendor_id = ven.id
LEFT JOIN users AS cb ON act.created_by = cb.id
LEFT JOIN users AS ub ON act.updated_by = ub.id 
	
WHERE
 (
 	$P{request_no} is null
 	or act.request_no = $P{request_no}
 )
 AND (
 	$P{start_date} IS NULL
 	OR $P{end_date} IS NULL
 	OR DATE(act.reference_updated_at) BETWEEN CAST($P{start_date} AS DATE)
 	AND CAST($P{end_date} AS DATE)
 )
 AND
(
       $P{payment_status}    IS NULL OR act.payment_status =  $P{payment_status} 
)
AND 
(
  $P{status}  IS NULL OR INITCAP(REPLACE(act.status, '_', ' ')) = $P{status} 
 ) 
group by 
	activity_id,
	act.request_no,
	act.gl_category,
	rc.name,
	act.status,
	cb.employee_id,
	ub.employee_id,
	rb.employee_id,
	act.requested_by,
	rb_adv.employee_id,
	act.payment_status
	--ven.code