SELECT
    advances.*,
    COALESCE(SUM(sett_l.approved_expense), 0) AS settlement,
    COALESCE(SUM(sett_l.approved_expense), 0) - advances.approved_expense AS balance
FROM
    (
        SELECT
            act.request_no AS activity_id,
            adv_l.request_id AS adv_hdr_id,
            act.gl_category,
            rc.name AS category,
            INITCAP(REPLACE(act.status, '_', ' ')) request_status,
            us.employee_id AS name,
            COALESCE(ven.code, '') AS vendor,
            COALESCE(rb.employee_id, '') AS requested_by,
            COALESCE(cb.employee_id, '') AS created_by,
            COALESCE(ub.employee_id, '') AS updated_by,
            SUM(adv_l.approved_expense) AS approved_expense
        FROM
            requests act
            JOIN request_lines adv_l ON act.id = adv_l.reference_request_id
            JOIN requests hdr ON adv_l.request_id = hdr.id
            JOIN users us ON hdr.requested_by = us.id
            LEFT JOIN request_categories AS rc ON act.request_category_id = rc.id
            LEFT JOIN users AS rb ON act.requested_by = rb.id
            LEFT JOIN vendors AS ven ON act.vendor_id = ven.id
            LEFT JOIN users AS cb ON act.created_by = cb.id
            LEFT JOIN users AS ub ON act.updated_by = ub.id -- JOIN request_lines sett_l ON adv_l.request_id = sett_l.reference_request_id
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
            AND act.id IN (
                WITH RECURSIVE referenced_requests AS (
                    -- Step 1: Identify all requests that are referenced in other request_lines
                    SELECT
                        DISTINCT rl.reference_request_id AS request_id
                    FROM
                        request_lines rl
                    WHERE
                        rl.reference_request_id IS NOT NULL
                ),
                referencing_requests AS (
                    -- Step 2: Identify all requests whose lines contain references to other requests
                    SELECT
                        DISTINCT rl.request_id as request_id
                    FROM
                        request_lines rl
                    WHERE
                        rl.reference_request_id IS NOT NULL
                ) -- Step 3: Select requests from referenced_requests that are not in referencing_requests
                SELECT
                    rr.request_id
                FROM
                    referenced_requests rr
                    LEFT JOIN referencing_requests rrf ON rr.request_id = rrf.request_id
                where
                    rrf.request_id is null
            )
        GROUP BY
            act.request_no,
            adv_l.request_id,
            us.employee_id,
            act.gl_category,
            rc.name,
            act.status,
            cb.employee_id,
            ub.employee_id,
            rb.employee_id,
            act.requested_by,
            ven.code
    ) advances
    LEFT JOIN request_lines sett_l ON advances.adv_hdr_id = sett_l.reference_request_id
GROUP BY
    advances.activity_id,
    advances.adv_hdr_id,
    advances.name,
    advances.approved_expense,
    advances.gl_category,
    advances.category,
    advances.request_status,
    advances.vendor,
    advances.created_by,
    advances.updated_by,
    advances.requested_by