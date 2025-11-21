<script>
    let rows_id = 1; // Initial row number
    let row_function_id = {}; 
	//toastr.options = {"positionClass": "toast-top-center",}



	function dropdownSelect(left, right){
		return (left == right?'selected':'');
	}
	function change_action(element){
		let my_action = $(element).val()
		count = 0;
		$('.actions').each(function(key,elem){
			if($(elem).val() == my_action){
				count++;
			}
		})
		if(count>1){
			$(element).val("");
            toastr.options = {"positionClass": "toast-top-right",}
			toastr.error("this action name already taken","Error");
		}
		
	}
	function change_function(element){
		let my_action = $(element).val()
		count = 0;
		$('.func').each(function(key,elem){
			if($(elem).val() == my_action){
				count++;
			}
		})
		if(count>1){
			$(element).val("");
            toastr.options = {"positionClass": "toast-top-right",}
            toastr.error("this function name already taken","Error")
			
		}
		
	}
	function addClass(row_id, class_data = {}){
		console.log(class_data);
		let function_id = row_function_id[row_id];
		// function_id = 1;
		

		let row_index = row_id-1;
		// let function_index = function_id-1;

		let row  = `
			<tr id='row_func_${row_id}_${function_id}'>
                <td>
					<input type="hidden"  id="structure_id_${row_id}_${function_id}" value="${class_data.id??""}" name="module_function_id[${row_index}][]">
					<input type="text" value="${class_data.method??""}" id="structure_function_${row_id}_${function_id}"  class="form-control func" onchange="change_function(this)" placeholder ="Class Function Name" name="function[${row_index}][]" required></td>
                <td>
                    <select class="form-control" id="structure_return_${row_id}_${function_id}" name="return_type[${row_index}][]">
                        <option value="view" ${dropdownSelect("view",class_data.return_type)} >view</option>
                        <option value="json" ${dropdownSelect("json",class_data.return_type)} >json</option>
                    </select>
                </td>
				<td>
                    <button type="button" class="btn btn-outline-danger btn-sm float-end" onclick="deleteRowFunction('${row_id}','${function_id}')" data-toggle="tooltip" data-placement="left" title="Remove Row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
				</td>
			</tr>
		`;
		

		$('#structure_class_'+row_id).append(row);
		row_function_id[row_id]++;
	}
    function addRow(structure = {}) {
		// console.log(structure);
		row_id = rows_id;
		row_index = rows_id-1;
		row_function_id[row_id] = 1;

        let newRow = `
            <tr id="structure_${row_id}"}">
                <td>
                    ${row_id}
                    <input type="hidden"  id="structure_id_${row_id}" value="${structure.id??""}" name="rowIds[${row_index}]">
                </td>
                <td><input type="text" value="${structure.action??""}"  id="structure_action_${row_id}"  class="form-control actions" onchange="change_action(this)"  placeholder ="Permission Name" name="action[${row_index}]" autofocus required></td>

				<td>
					<table style='width:100%'>
						<thead>
							<tr>
								<th>Function</th>
								<th>Return</th>
								<th>
									<div class='btn btn-outline-primary  float-end btn-sm' onclick="addClass(${row_id})">
										<svg xmlns="" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
											<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
											<line x1="12" y1="8" x2="12" y2="16"></line>
											<line x1="8" y1="12" x2="16" y2="12"></line>
										</svg>
									</div>
								</th>
							</tr>
						<thead>
						<tbody id="structure_class_${row_id}">
						</tbody>
					</table>
					
				</td>
				<td>
					<input type="hidden" value="0"  id="structure_is_read_${row_id}"  name="is_read[${row_index}]" >
					<input type="checkbox" ${structure.is_read == 1?"checked":""} value="1" onclick="check_tick(this)" id="structure_is_read_${row_id}"  name="is_read[${row_index}]" >
				</td>
                <td><input type="text" value="${structure.denial_msg??""}" class="form-control" id="structure_msg_${row_id}" placeholder = "You Are Not Allowed" name="denial_msg[${row_index}]" required></td>
                <td>
                    <button type="button" class="btn btn-outline-danger  float-end" onclick="deleteRow('${row_id}')" data-toggle="tooltip" data-placement="left" title="Remove Row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
                </td>
            </tr>
        `;
		

        $('#tableBody').append(newRow);
        rows_id++;
		return row_id;
		}
	function check_tick(element) {
    	
    	let count = 0;
    	$("input[name^='is_read']").each(function(key, elem) {
        	if ($(elem).val() == 1 && $(elem).prop("checked")) {
            console.log($(elem).val());
            count++;
        }
    	});
    	if (count > 1) {
        	$(element).prop("checked", false);
			toastr.options = {"positionClass": "toast-top-right",}
			toastr.error("Only One Permission Type Can Be Is Read","Error");
        	
    		}
		}
	
	function deleteRowFunction(row_id, func_id){
		if(confirm("Are you sure you want to delete this row function?")){
			let structure_id = $('#structure_id_'+row_id+'_'+func_id).val();
			console.log(structure_id);
			$('#row_func_'+row_id+'_'+func_id).remove();
			toastr["options"] = {"positionClass": "toast-top-right",}
			toastr.success("Row Function Deleted Successfuly","Success");

			
		}
	}
    function deleteRow(row_id) {
        
        if (confirm("Are you sure you want to delete this row?")) {

			let structure_id = $('#structure_id_'+row_id).val();
			console.log(structure_id);
			if(structure_id){
			$.post('/delete-row/' + structure_id, {
				"_token":"{{csrf_token()}}",
				"_method":"DELETE",
			}).then(function(data){
				console.log(data);
				$("#structure_"+row_id).remove();
				toastr.options = {"positionClass": "toast-top-right",}
				toastr.success("Row Removed Successfully", 'Success');

			}).fail(function(xhr){
				toastr.options = {"positionClass": "toast-top-right",}
				toastr.error(xhr.responseJSON.error, 'Error');

			})
		}else{
            $("#structure_"+row_id).remove();
			toastr.options = {"positionClass": "toast-top-right",}
			toastr.success("Row Removed Successfully", 'Success');

		}
			
        }
    }


	async function load_edit(){
		@if (isset($edit))
			@foreach ($roleModule->role_permission_type as  $structure)
				row_id = await addRow({!! $structure !!});
				@foreach ( $structure->rolePermissionTypeFunctions as $method)
					await addClass(row_id, {!! $method !!})
				@endforeach
			@endforeach
		@else
			
			// --------------------------------------
			read_id = await addRow({
				'action':'read',
				'is_read':1,
				'denial_msg':'You are not allowed to read ',
			});
			await addClass(read_id, {
				'method':'index',
				'return_type':'view',
			})
			await addClass(read_id, {
				'method':'show',
				'return_type':'view',
			})
			
			// --------------------------------------
			create_id = await addRow({
				'action':'create',
				'is_read':0,
				'denial_msg':'You are not allowed to create ',
			});
			await addClass(create_id, {
				'method':'create',
				'return_type':'view',
			})
			
			await addClass(create_id, {
				'method':'store',
				'return_type':'view',
			})

			// --------------------------------------
			update_id = await addRow({
				'action':'update',
				'is_read':0,
				'denial_msg':'You are not allowed to update ',
			});
			await addClass(update_id, {
				'method':'edit',
				'return_type':'view',
			})
			await addClass(update_id, {
				'method':'update',
				'return_type':'view',
			})
			//-----------------------------------------
			delete_id = await addRow({
				'action':'delete',
				'is_read':0,
				'denial_msg':'You are not allowed to delete ',
			});
			await addClass(delete_id, {
				'method':'delete',
				'return_type':'view',
			})
			//....................................
			global_id = await addRow({
				'action':'global',
				'is_read':0,
				'denial_msg':'You are not allowed globally ',
			});
			

		@endif
		
	}
	$(document).ready(function(){
		
		load_edit();
		
		
	})

</script>
<script>
    $(document).ready(function() {
        const actionInput = $(`#structure_action_${row_id}`);

        // Add event listener for keydown event
        actionInput.on('keydown', function(event) {
            // Check if the pressed key is space (keyCode 32) and prevent default behavior
            if (event.keyCode === 32) {
                event.preventDefault();
            }
        });
    });
</script>

