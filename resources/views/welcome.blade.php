<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Todo List</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
        <style>
            .strike_none{
                text-decoration:none;
            }
            .green{
            background-color:green !important;
            }
            ul {
                list-style-type: none;
                padding: 0;
            }
            .status-green {
                background-color: lightgreen;
                color: white;
                font-size: medium;
                font-weight: bold;
            }
            .status-red {
                background-color: lightcoral;
                color: white;
                font-size: medium;
                font-weight: bold;
            }
            li {
                padding: 8px 16px;
                margin-bottom: 8px;
                border-radius: 4px;
            }
        </style>
    </head>
    <body class="antialiased  bg-[#cbd7e3]">
        <div class="flex justify-center items-center  p-5">
            <form class="mt-5">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="editId" id="editId" value="">
                <input type="text" id="title" name="title" placeholder="Enter your new task..." class=" px-4 py-2 mr-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
                <button id="btnAddtask" type="button" class="bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300 p-2">Add Task</button>
            </form>
        </div>
        <p class="flex text-red-600 justify-center items-center " id="backEndError"></p>

        <div class="flex justify-center ">

            <div class="h-auto  w-96 bg-white rounded-lg p-4">
                <div class="flex" style="justify-content: end">
                    <button class="text-l font-semibold mt-2 text-[#063c76]" onclick="openModal('modelConfirm')">
                        All Task List
                    </button>
                </div>

                 <div id="modelConfirm" class="fixed hidden z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 ">
                     <div class="relative top-20 mx-auto shadow-xl rounded-md bg-white max-w-md">

                         <div class="flex justify-end p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="150" height="100" viewBox="0 0 200 100">
                                <!-- Background Rectangle -->
                                <rect x="10" y="10" width="100" height="50" rx="10" ry="10" fill="#e0e0e0"></rect>

                                <!-- Success Text -->
                                <text x="30" y="30" font-family="Arial" font-size="12" fill="green">Completed</text>

                                <!-- Removed Text -->
                                <text x="30" y="50" font-family="Arial" font-size="12" fill="red">Not Completed</text>
                              </svg>
                             <button style="height: 30px;" onclick="closeModal('modelConfirm')" type="button"
                                 class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                                 <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                     <path fill-rule="evenodd"
                                         d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                         clip-rule="evenodd"></path>
                                 </svg>
                             </button>
                         </div>

                         <div id="allTaskList" class="p-6 pt-0 text-center">
                            <div id="list-container">
                                <ul id="dynamic-list">
                                  <!-- List items will be dynamically appended here -->
                                </ul>
                              </div>
                         </div>

                     </div>
                 </div>
                <p class="text-xl font-semibold mt-2 text-[#063c76]">To-do List</p>
                <ul class="my-4 " id="taskList">
                    @forelse ($tasks as $task )
                        <li class=" mt-4" id="1">
                            <div class="flex gap-2">
                                <div class="w-9/12 h-12 bg-[#e0ebff] rounded-[7px] flex justify-start items-center px-3">
                                    <span id="check{{ $task['id'] }}" class=" w-4 h-4 bg-white rounded-full border border-white transition-all cursor-pointer hover:border-[#36d344] flex justify-center items-center" onclick="checked({{ $task['id'] }})"><i class="text-white fa fa-check"></i></span>
                                    <strike id="strike{{ $task['id'] }}" class="strike_none text-sm ml-4 text-[#5b7a9d] font-semibold">{{ $task['title'] }}</strike>
                                </div>
                                <span class="w-1/4 h-12 bg-[#e0ebff] rounded-[7px] flex justify-center text-sm text-[#5b7a9d] font-semibold items-center ">
                                    <button type="button" class="m-2 " onclick="editTask({{ $task['id'] }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="blue" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <button type="button" class="m-2" onclick="deleteTask({{ $task['id'] }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </span>
                            </div>
                        </li>
                    @empty
                        <li class=" mt-4" id="1">
                            <div class="flex gap-2">
                            {{ 'No Task Present' }}
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
        <script type="text/javascript">
            window.openModal = function(modalId) {
                document.getElementById(modalId).style.display = 'block'
                document.getElementsByTagName('body')[0].classList.add('overflow-y-hidden')
                $.ajax({
                    type: "get",
                    url: 'getAllData',
                    contentType: "application/json",
                    dataType: "json",
                    success: function(data){
                        const data1 =data.task

                        function createListItems() {
                        const ul = document.getElementById("dynamic-list");

                        data1.forEach(item => {
                        const li = document.createElement("li");
                        li.textContent = `${item.title}`;

                        // Set class based on status
                        if (item.completed === 1) {
                            li.classList.add("status-green");
                        }

                        if(item.completed===0){
                            li.classList.add("status-red");
                        }

                        ul.appendChild(li);
                        });
                    }

                    // Call the function to populate the list on page load
                    createListItems();

                    },
                    error: function(data){
                        alert('Error');
                    }
                });

            }

            window.closeModal = function(modalId) {
                document.getElementById(modalId).style.display = 'none'
                document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
                resetModal();
            }
            function resetModal() {
                const ul = document.getElementById("dynamic-list");
                ul.innerHTML = ""; // Clear all list items
            }
            // Close all modals when press ESC
            document.onkeydown = function(event) {
                event = event || window.event;
                if (event.keyCode === 27) {
                    document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
                    let modals = document.getElementsByClassName('modal');
                    Array.prototype.slice.call(modals).forEach(i => {
                        i.style.display = 'none'
                    })
                }
            };
        </script>
        <script>

            function checked(id){
                var checked_green=document.getElementById("check"+id);
                checked_green.classList.toggle('green');
                var strike_unstrike=document.getElementById("strike"+id);
                strike_unstrike.classList.toggle('strike_none');
                $.ajax({
                        type: "POST",
                        url:"completedTask",
                        contentType: "application/json",
                        dataType: "json",
                        data: JSON.stringify({
                            "_token": $('#token').val(),
                            id: id,
                            status:1
                        }),
                        success: function(response) {
                            if(response.success==true){
                                $("#taskList").load(location.href + " #taskList");
                            }
                            $("#backEndError").text('')
                        },
                        error: function(response) {
                            console.log(response);
                        }
                });

            }

            $("#btnAddtask").click(function(){
                $.ajax({
                        type: "POST",
                        url:"createTask",
                        contentType: "application/json",
                        dataType: "json",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: JSON.stringify({
                            id:$("#editId").val(),
                            title: $("#title").val(),
                            "_token": $('#token').val()
                        }),
                        success: function(response) {
                            // console.log(response.success);
                            if(response.success==true){
                                $("#taskList").load(location.href + " #taskList");
                                $("#title").val('')
                                $("#editId").val('')
                            }
                            $("#backEndError").text('')

                        },
                        error: function(response) {
                            // console.log();
                            $("#backEndError").text(response.responseJSON.message)
                        }
                });
            })

            function editTask(id){
                $.ajax({
                        type: "POST",
                        url:"editTask",
                        contentType: "application/json",
                        dataType: "json",
                        data: JSON.stringify({
                            "_token": $('#token').val(),
                            id: id,
                        }),
                        success: function(response) {
                            console.log(response.task.title);
                            $("#editId").val(response.task.id);
                            $("#title").val(response.task.title)
                            $("#backEndError").text('')
                        },
                        error: function(response) {
                            console.log(response);
                        }
                });
            }

            function deleteTask(id){

                Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                        type: "POST",
                        url:"deleteTask",
                        contentType: "application/json",
                        dataType: "json",
                        data: JSON.stringify({
                            "_token": $('#token').val(),
                            id: id,
                            removed:1
                        }),
                        success: function(response) {
                            if(response.success==true){
                                $("#taskList").load(location.href + " #taskList");
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                });
                    // Swal.fire({
                    //     title: "Deleted!",
                    //     text: "Your file has been deleted.",
                    //     icon: "success"
                    //     });
                    }
                });


            }
        </script>
    </body>
</html>
