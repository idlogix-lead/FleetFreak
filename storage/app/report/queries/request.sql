SELECT  r.created_at,r.request_no,r.status,r.total_expense,r.approved_expense,r.requested_by,rq.name req_cat,r.description,rt.name req_type
,rqd.col_name columns,u.name user,u.lname,rl.detail cat_detail,rl.expense cat_expense,rlf.value,rl.approved_expense cat_exp
	FROM requests r 
LEFT JOIN request_categories rq ON r.request_category_id=rq.id
LEFT JOIN request_types rt ON rq.id=rt.request_category_id 

LEFT JOIN request_type_details rqd ON rt.id=rqd.request_type_id AND rqd.is_disabled!=1 --AND rqd.is_other!=1
	LEFT JOIN request_lines rl on rt.id=rl.request_type_id AND r.id=rl.request_id
	LEFT JOIN request_line_infos rlf ON rl.id=request_line_id AND rqd.id=rlf.request_type_detail_id
	LEFT JOIN users u ON r.requested_by=u.id 
WHERE r.id= $P{print} 
AND rt.status!='disabled'
GROUP BY RT.NAME, r.request_no,r.status,r.total_expense,r.approved_expense,r.requested_by,rq.name,r.description
,rqd.col_name,u.name,u.lname,r.created_at,rl.detail,rl.expense,rlf.value,rl.approved_expense



-- 2nd attempt
SELECT  r.created_at,r.request_no,r.status,r.total_expense,r.approved_expense,r.requested_by,rq.name req_cat,r.description,rt.name req_type
,rqd.col_name columns,u.name user,u.lname,rl.detail cat_detail,rl.expense cat_expense,rlf.value,rl.approved_expense cat_exp
	FROM requests r
LEFT JOIN request_categories rq 	ON r.request_category_id=rq.id
	LEFT JOIN request_lines rl on  r.id=rl.request_id

LEFT JOIN request_types rt ON rl.request_type_id=rt.id
		
LEFT JOIN request_type_details rqd ON rt.id=rqd.request_type_id AND rqd.is_disabled!=1 
	LEFT JOIN request_line_infos rlf ON rl.id=request_line_id AND rqd.id=rlf.request_type_detail_id
	LEFT JOIN users u ON r.requested_by=u.id
WHERE r.id= 482
and rt.status!='disabled'
 --AND rqd.is_disabled!=1
	GROUP BY RT.NAME, r.request_no,r.status,r.total_expense,r.approved_expense,r.requested_by,rq.name,r.description
,rqd.col_name,u.name,u.lname,r.created_at,rl.detail,rl.expense,rlf.value,rl.approved_expense


-- final attempt running
SELECT r.created_at, r.request_no, r.status, r.total_expense, r.approved_expense, r.requested_by, 
       rq.name req_cat, r.description, rt.name req_type, rqd.col_name columns, u.name user, 
       u.lname, rl.detail cat_detail, rl.expense cat_expense, rlf.value, rl.approved_expense cat_exp
FROM requests r
LEFT JOIN request_categories rq 	ON r.request_category_id=rq.id
	LEFT JOIN request_lines rl on  r.id=rl.request_id

LEFT JOIN request_types rt ON rl.request_type_id=rt.id
LEFT JOIN request_line_infos rlf ON rl.id=rlf.request_line_id
		
LEFT JOIN request_type_details rqd ON rqd.id=rlf.request_type_detail_id
LEFT JOIN users u ON r.requested_by = u.id
WHERE r.id = $P{print}
GROUP BY RT.NAME, r.request_no, r.status, r.total_expense, r.approved_expense, r.requested_by, 
         rq.name, r.description, rqd.col_name, u.name, u.lname, r.created_at, rl.detail, 
         rl.expense, rlf.value, rl.approved_expense;
