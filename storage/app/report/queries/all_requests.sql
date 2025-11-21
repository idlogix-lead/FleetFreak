WITH request_details AS (SELECT
    r.id,
    r.request_no,
    r.gl_category,
    r.total_expense AS request_expense,
    r.approved_expense,
    INITCAP(REPLACE(r.status, '_', ' ')) AS request_status,
    rc.name AS category,
    COALESCE(rb.employee_id, '') AS requested_by,
    COALESCE(ven.code, '') AS vendor,
    COALESCE(cb.employee_id, '') AS created_by,
    COALESCE(ub.employee_id, '') AS updated_by,
    
    r.payment_status
    --CASE 
     --   WHEN (COUNT(rp.id) > 0) THEN 'Paid'
     --   ELSE 'Unpaid'
   -- END AS payment_status
FROM requests AS r
LEFT JOIN request_categories AS rc ON r.request_category_id = rc.id
LEFT JOIN users AS rb ON r.requested_by = rb.id
LEFT JOIN users AS cb ON r.created_by = cb.id
LEFT JOIN users AS ub ON r.updated_by = ub.id
LEFT JOIN vendors AS ven ON r.vendor_id = ven.id
LEFT JOIN request_payments AS rp ON r.id = rp.request_id
WHERE r.id NOT IN (
        SELECT COALESCE(rl.request_id, 0)
        FROM request_lines rl
        JOIN requests rf ON rl.reference_request_id = rf.id
        UNION 
        SELECT DISTINCT COALESCE(reference_request_id, 0)
        FROM request_lines
    )
    AND rc.type IS NULL
    AND r.status != 'draft'
    AND (
        $P{request_no} IS NULL OR r.request_no = $P{request_no}
    ) 
    AND (
        $P{category_id} IS NULL OR rc.id = $P{category_id}
    )
    AND (
        $P{start_date} IS NULL OR $P{end_date} IS NULL OR 
        DATE(r.created_at) BETWEEN CAST($P{start_date} AS DATE) AND CAST($P{end_date} AS DATE)
    )
GROUP BY
    r.id,
    r.request_no,
    r.gl_category,
    r.total_expense,
    r.approved_expense,
    r.status,
    rc.name,
    rb.employee_id,
    ven.code,
    cb.employee_id,
    ub.employee_id,
    r.payment_status
)

SELECT *
FROM request_details
WHERE
	(
       $P{payment_status}    IS NULL OR payment_status =  $P{payment_status} 
    )
    AND (
        $P{status}  IS NULL OR request_status = $P{status} 
    ) 
ORDER BY id DESC