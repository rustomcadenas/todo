  
<?php include('inc/header.php'); ?> 
<div class="container">  
    <?php include('inc/nav.php'); ?> 

    <div id="todo" v-cloak>
         
        <center><h3><a href="">Tomas Cadenas</a></h3></center>
        <div class="form-home" action="actions/signin.php" method="post">
            <div class="row" style="margin-bottom: 20px;"> 
                    <div class="col-lg-12">
                        <div class="input-group">
                        <input type="text" class="form-control" placeholder="Add new TODO" v-model="txt_todo" v-on:keyup.enter="addTodo">
                        <span class="input-group-btn">
                            <button class="btn btn-success" type="button" v-on:click="addTodo" >ADD</button>
                        </span>
                        </div><!-- /input-group -->
                    </div><!-- /.col-lg-6 -->
            </div><!-- /.row -->            
        </div>

        <div class="row">
            <div class="scroll"> 
                <div class="list-group"> 
                    <div  v-for="row in allData"> 

                        <div class="input-group" v-if="row.todo != 'Empty'"> 
                            <span class="input-group-btn" >
                                <button class="btn btn-primary" style="padding-top: 9px; padding-bottom: 9px;" type="button" v-on:click="fetchSelected(row.id)">Edit</button>
                            </span>
                            <p href="#" class="list-group-item">{{ row.todo }}</p> 
                            <span class="input-group-btn">
                                <button class="btn btn-success" style="padding-top: 9px; padding-bottom: 9px;" type="button" v-on:keyup.enter="doneTodo(row.id)" v-on:click="doneTodo(row.id)">Done</button>
                            </span>
                        </div> 

                        <div class="input-group col-sm-12" v-if="row.todo == 'Empty'"> 
                            <p href="#" class="list-group-item">{{ row.todo }}</p>  
                        </div>   
                    </div> 
                </div>
            </div> 
        </div> 

        <div v-if="edit_modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                            <button type="button" class="close" v-on:click="edit_modal=false">&times;</button>
                            <h4 class="modal-title">Edit Todo</h4>
                            </div>
                            <div class="modal-body">
                                <input type="text" class="form-control" v-model="txt_editTodo">
                                <input type="hidden" class="form-control" v-model="txt_editID">
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-success" v-on:click="updateTodo">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
              






     </div>   <!-- app -->

        <!-- development version, includes helpful console warnings -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script>
        var app = new Vue({
            el: '#todo',
            data: {
                txt_todo: '',
                allData: '',
                txt_editTodo: '',
                txt_editID: '',
                edit_modal: false
            },
            methods: {
                addTodo: function(){
                    if(this.txt_todo != ''){
                        axios.post('actions/todo.php', {
                            action: 'insert',
                            txtTodo: app.txt_todo
                        }).then(function(response){
                            app.txt_todo = '';
                            app.fetchAllData();
                        });
                    }else{
                        alert("Cant add empty fields. ");
                    }
                },

                fetchAllData: function(){
                    axios.post('actions/todo.php', {
                        action: 'fetchAll'
                    }).then(function(response){
                        app.allData = response.data
                    });
                }, 
                fetchSelected: function(id){
                    axios.post('actions/todo.php', {
                        action: 'fetchSelected',
                        id: id
                    }).then(function(response){ 
                        app.txt_editTodo = response.data.todo;
                        app.txt_editID = response.data.id;
                        app.edit_modal = true;
                    });
                },
                doneTodo: function(id){
                    axios.post('actions/todo.php', {
                        action: 'done',
                        id: id
                    }).then(function(response){ 
                        app.fetchAllData();
                    });
                },
                updateTodo: function(){
                    if(this.txt_editTodo != ''){
                        axios.post('actions/todo.php', {
                            action: 'updateSingle',
                            id: app.txt_editID,
                            todo: app.txt_editTodo
                         }).then(function(response){ 
                            app.fetchAllData();
                            app.edit_modal = false;
                            app.txt_editID = '';
                            app.txt_editTodo = ''; 
                         });
                    }else{
                        alert("Cannot be empty");
                    }
                   
                },
                openModal: function(){
                     app.edit_modal = true;
                }, 
            }, 
            created: function(){
                this.fetchAllData();
            }
        });

    </script>
</div>  
<?php include('inc/footer.php'); ?>
 