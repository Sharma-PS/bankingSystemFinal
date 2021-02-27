(function ($) {
 "use strict";
 // Validation for order form
 $(".add-professors").validate(
	{					
		rules:
		{	
			fullname:
			{
				required: true
			},
			address:
			{
				required: true
			},
			mobileNo:
			{
				required: true
			},
			NIC:
			{
				required: true
			},
			dob:
			{
				required: true
			},
			email:
			{
				required: true
			},
			job:
			{
				required: true
			},
			branchCode:
			{
				required: true
			},
			password:
			{
				required: true,
				minlength: 3,
				maxlength: 20
			},
			confirmpassword:
			{
				required: true,
				minlength: 3,
				maxlength: 20
			}
		},
		messages:
		{	
			fullname:
			{
				required: 'Please enter full name'
			},
			
			address:
			{
				required: 'Please enter your address'
			},
			mobileNo:
			{
				required: 'Please enter Mobile Number'
			},
			dob:
			{
				required: 'Please enter Date of Birth'
			},
			email:
			{
				required: 'Please enter your email address',
				email: 'Please enter a VALID email address'
			},
			job:
			{
				required: 'Please select the Designation'
			},
			branchCode:
			{
				required: 'Please enter the Branch Code'
			},
			password:
			{
				required: 'Please enter the password'
			},
			confirmpassword:
			{
				required: 'Please confirm the password'
			},
			
		},			
			errorPlacement: function(error, element)
			{
				error.insertAfter(element.parent());
			}
		});
		
 
 // Validation for order form
		$("#acount-infor").validate(
		{					
			rules:
			{	
				email:
				{
					required: true,
					email: true
				},
				phoneno:
				{
					required: true
				},
				password:
				{
					required: true,
					minlength: 3,
					maxlength: 20
				},
				confarmpassword:
				{
					required: true,
					minlength: 3,
					maxlength: 20
				}
			},
			messages:
			{	
			
				email:
				{
					required: 'Please enter your email address',
					email: 'Please enter a VALID email address'
				},
				phoneno:
				{
					required: 'Please enter mobile number'
				},
				password:
				{
					required: 'Please enter your password'
				},
				confarmpassword:
				{
					required: 'Please enter your confarm password'
				}
				
			},					
			
			errorPlacement: function(error, element)
			{
				error.insertAfter(element.parent());
			}
		});
 
 // Validation for order form
		$(".addaccount").validate(
		{					
			rules:
			{	
				nic:
				{
					required: true
				},
				branch:
				{
					required: true
				},
				balance:
				{
					required: true,
					min:0
				},
				acctype:
				{
					required: true
				},
				imageico:
				{
					required: true
				},
				department:
				{
					required: true
				},
				description:
				{
					required: true
				},
				crprofessor:
				{
					required: true
				},
				year:
				{
					required: true
				},
				email:
				{
					required: true,
					email: true
				},
				phoneno:
				{
					required: true
				},
				password:
				{
					required: true,
					minlength: 3,
					maxlength: 20
				},
				confarmpassword:
				{
					required: true,
					minlength: 3,
					maxlength: 20
				}
			},
			messages:
			{	
				nic:
				{
					required: 'Please enter Customer NIC'
				},
				branch:
				{
					required: 'You Change the source.'
				},
				balance:
				{
					required: 'Enter initial balance'
				},
				acctype:
				{
					required: 'Select type of account'
				},
				imageico:
				{
					required: 'Please enter image'
				},
				department:
				{
					required: 'Please enter department'
				},
				description:
				{
					required: 'Please enter description'
				},
				crprofessor:
				{
					required: 'Please enter course professor'
				},
				year:
				{
					required: 'Please enter year'
				},
				email:
				{
					required: 'Please enter your email address',
					email: 'Please enter a VALID email address'
				},
				phoneno:
				{
					required: 'Please enter mobile number'
				},
				password:
				{
					required: 'Please enter your password'
				},
				confarmpassword:
				{
					required: 'Please enter your confarm password'
				}
				
			},					
			
			errorPlacement: function(error, element)
			{
				error.insertAfter(element.parent());
			}
		});

		// Validation for login form
		$(".add-branch").validate(
			{					
				rules:
				{	
					branch_name:
					{
						required: true
					},
					branch_code:
					{
						required: true
					},
					branch_type:
					{
						required: true
						
					},
					contact:
					{
						required: true,
						minlength: 10,
						maxlength: 10
	
					},
					status:
					{
						required: true
					},
					open:
					{
						required: true
					},
					branch_address:
					{
						required: true
					}
				},
				messages:
				{	
					branch_name:
					{
						required: "Please Type Branch Name"
					},
					branch_code:
					{
						required: "Please Enter the BranchCode "
					},
					branch_type:
					{
						required: "Please Select the Branch Type"
					},
					contact:
					{
						required: "Please Enter the Branch Contact number",
						minlength : "Please Enter valid Branch Contact number",
						maxlength : "Please Enter valid Branch Contact number"
					},
					status:
					{
						required: "Please Select the Status of Branch"
					},
					open:
					{
						required: "Please Select the Open date of Branch"
					},
					branch_address:
					{
						required: "Please Enter the Branch address"
					}
				},					
				
				errorPlacement: function(error, element)
				{
					error.insertAfter(element.parent());
				}
			});


			// Validation for login form
		$(".addaccountFD").validate(
			{					
				rules:
				{	
					sacid:
					{
						required: true
					},
					balance:
					{
						required: true
					},
					acctype:
					{
						required: true						
					}
				},
				messages:
				{	
					sacid:
					{
						required: "Saving Account ID Required"
					},
					balance:
					{
						required: "Enter Amount"
					},
					acctype:
					{
						required: "Please select option"
					}
				},					
				
				errorPlacement: function(error, element)
				{
					error.insertAfter(element.parent());
				}
			});

			$(".addaccountloan").validate(
				{					
					rules:
					{	
						nic:
						{
							required: true
						},
						balance:
						{
							required: true
						},
						reason:
						{
							required: true						
						},
						durationInMonth:{
							required: true	
						},
						planId:{
							required: true	
						}
					},
					messages:
					{	
						nic:
						{
							required: "NIC ID Required."
						},
						balance:
						{
							required: "Enter Amount."
						},
						reason:
						{
							required: "Please Write description for Your request."
						},
						durationInMonth:{
							required: "Type Duration in Month"	
						},
						planId:{
							required: "Please Select Your loan Plan ID."	
						}
					},					
					
					errorPlacement: function(error, element)
					{
						error.insertAfter(element.parent());
					}
				});

	// Validation for login form
			$(".edit-branch").validate(
				{					
					rules:
					{	
						branch_name:
						{
							required: true
						},
						branch_type:
						{
							required: true
							
						},
						contact:
						{
							required: true,
							minlength: 10,
							maxlength: 10
		
						},
						branch_address:
						{
							required: true
						}
					},
					messages:
					{	
						branch_name:
						{
							required: "Please Type Branch Name"
						},
						branch_type:
						{
							required: "Please Select the Branch Type"
						},
						contact:
						{
							required: "Please Enter the Branch Contact number",
							minlength : "Please Enter valid Branch Contact number",
							maxlength : "Please Enter valid Branch Contact number"
						},
						branch_address:
						{
							required: "Please Enter the Branch address"
						}
					},					
					
					errorPlacement: function(error, element)
					{
						error.insertAfter(element.parent());
					}
				});

		$(".depositMoney").validate(
			{					
				rules:
				{	
					accID:
					{
						required: true
					},
					amountOfMoney:
					{
						required: true
					}
				},
				messages:
				{						
					accID:
					{
						required: "Please enter customer Account No."
					},
					amountOfMoney:
					{
						required: "Please enter amount of transfer money."
					}
				},					
				
				errorPlacement: function(error, element)
				{
					error.insertAfter(element.parent());
				}
			});

			$(".TransferMoney").validate(
				{					
					rules:
					{	
						FaccID:
						{
							required: true
						},
						TaccID:
						{
							required: true
						},
						amountOfMoney:
						{
							required: true
						}
					},
					messages:
					{						
						FaccID:
						{
							required: "Please enter customer Sender Account No."
						},
						TaccID:
						{
							required: "Please enter customer To Account No."
						},
						amountOfMoney:
						{
							required: "Please enter amount of transfer money."
						}
					},					
					
					errorPlacement: function(error, element)
					{
						error.insertAfter(element.parent());
					}
				});
		
		
		
		
 
	// Validation for login form
		$("#comment").validate(
		{					
			rules:
			{	
				name:
				{
					required: true
				},
				message:
				{
					required: true
				},
				email:
				{
					required: true,
					email: true
				}
			},
			messages:
			{	
				name:
				{
					required: 'Please enter your name'
				},
				message:
				{
					required: 'Please enter your message'
				},
				email:
				{
					required: 'Please enter your email address',
					email: 'Please enter a VALID email address'
				}
			},					
			
			errorPlacement: function(error, element)
			{
				error.insertAfter(element.parent());
			}
		});
		
		
	// Validation for login form
		$(".addlibrary").validate(
		{					
			rules:
			{	
				nameasset:
				{
					required: true
				},
				subject:
				{
					required: true
				},
				imageico:
				{
					required: true
				},
				type:
				{
					required: true
				},
				price:
				{
					required: true
				},
				year:
				{
					required: true
				},
				status:
				{
					required: true
				},
				department:
				{
					required: true
				},
				email:
				{
					required: true,
					email: true
				}
			},
			messages:
			{	
				nameasset:
				{
					required: 'Please enter your name of assets'
				},
				subject:
				{
					required: 'Please enter your subject'
				},
				imageico:
				{
					required: 'Please enter image'
				},
				department:
				{
					required: 'Please enter your department'
				},
				type:
				{
					required: 'Please enter library type'
				},
				price:
				{
					required: 'Please enter price'
				},
				year:
				{
					required: 'Please enter year'
				},
				status:
				{
					required: 'Please enter status'
				},
				email:
				{
					required: 'Please enter your email address',
					email: 'Please enter a VALID email address'
				}
			},					
			
			errorPlacement: function(error, element)
			{
				error.insertAfter(element.parent());
			}
		});
		
	// Validation for login form
		$(".add-department").validate(
		{					
			rules:
			{	
				name:
				{
					required: true
				},
				headofdepartment:
				{
					required: true
				},
				email:
				{
					required: true
				},
				phone:
				{
					required: true
				},
				noofstudent:
				{
					required: true
				},
				status:
				{
					required: true
				}
			},
			messages:
			{	
				name:
				{
					required: 'Please enter your name'
				},
				headofdepartment:
				{
					required: 'Please enter head of department'
				},
				email:
				{
					required: 'Please enter email'
				},
				phone:
				{
					required: 'Please enter your phone'
				},
				noofstudent:
				{
					required: 'Please enter no of student'
				},
				status:
				{
					required: 'Please enter status'
				}
			},					
			
			errorPlacement: function(error, element)
			{
				error.insertAfter(element.parent());
			}
		});
		
	// Validation for login form
		$("#send-mail").validate(
		{					
			rules:
			{	
				name:
				{
					required: true
				},
				headofdepartment:
				{
					required: true
				},
				email:
				{
					required: true,
					email: true
				},
				phone:
				{
					required: true
				},
				noofstudent:
				{
					required: true
				},
				status:
				{
					required: true
				}
			},
			messages:
			{	
				name:
				{
					required: 'Please enter your name'
				},
				headofdepartment:
				{
					required: 'Please enter head of department'
				},
				email:
				{
					required: 'Please enter email'
				},
				email:
				{
					required: 'Please enter your email address',
					email: 'Please enter a VALID email address'
				},
				noofstudent:
				{
					required: 'Please enter no of student'
				},
				status:
				{
					required: 'Please enter status'
				}
			},					
			
			errorPlacement: function(error, element)
			{
				error.insertAfter(element.parent());
			}
		});
		
 
})(jQuery); 