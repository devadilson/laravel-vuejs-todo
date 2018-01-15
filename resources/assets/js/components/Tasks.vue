<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">My Tasks</div>

                        <div class="panel-body">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Enter a new task.." v-model="task.name" @keydown.enter="create">
                                <span class="input-group-btn">
                                    <button class="btn btn-info" @click="create">Add</button>
                                </span>                           
                            </div>

                            <div class="tasks-list">                          
                                <div class="panel-heading" v-if="!tasks.length">
                                    <div class="alert alert-danger">
                                        You have no tasks, enjoy today!
                                    </div>
                                </div>

                                <ul class="list-unstyled">
                                    <li v-for="task in tasks" :key="task.id" :class="{ done: task.completed }">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" v-model="task.completed" @click="done(task)">{{ task.name }}
                                            </label>
                                            <div class="pull-right">
                                                <a href="#" v-show="!task.completed" data-toggle="modal" data-target="#myModal" @click="preencheFormulario(task)">Edit</a>
                                                <a href="#" @click.prevent="remove(task)">Delete</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    
                        <div class="panel panel-footer" v-if="tasks.length">
                            <span class="label label-default">You have {{ tasks.length }} tasks</span>
                            <span class="label label-warning">{{ remainingTasks() }} tasks left</span>
                            <span class="label label-success">{{ completedTasks() }} tasks completed</span>
                        </div>

                        <modal title="Update" id="myModal" nome="myModal">
                            <painel>
                                <form id="formEdit" method="put" action='action' >
                                    <input v-if="token" type="hidden" class="form-control" name="_token" v-bind:value="token">
                                    <input type="hidden" name="id" v-model="task.id" >
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" v-model="task.name" placeholder="Name" @@keydown.enter="updateTask">
                                    </div>                           
                                </form>                                
                            </painel>
                                <span slot="button">
                                    <button type="button" v-on:click="updateTask(task)" class="btn btn-primary">Update</button>
                                </span>
                        </modal>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['token'],

        mounted() {
            this.fetchData()
        },
        data() {
            return{
                tasks: [],
                task: {
                    name: ''
                }
            }
        },
        methods: {
            close: function () {
                $("#myModal .close").click()
                this.task.name = ''
                console.log('close')
            },

            preencheFormulario (task){
                axios.get(`/api/tasks/${task.id}`)
                .then(res => {
                    this.task = res.data
                })
                .catch((err) => {
                    console.log(err)
                })
            },
            remainingTasks () {
                return this.tasks.filter(task => {
                    return !task.completed
                }).length
            },

            completedTasks () {
                return this.tasks.filter(task => {
                    return task.completed
                }).length
            },

            fetchData () {
                axios.get('/api/tasks')
                .then((res) => {
                    this.tasks = res.data
                })
                .catch((err) => {
                    console.log(err)
                })
            },

            create (){
                axios.post('api/tasks', this.task)
                .then((res) => {
                    this.tasks.unshift(res.data)
                    this.task.name = ''
                })
                .catch((err) => {
                    console.log(err)
                })
            },

            remove (task) {
               axios.delete(`/api/tasks/${task.id}`)
                .then((res) => {
                    const taskIndex = this.tasks.indexOf(task)
                    this.tasks.splice(taskIndex, 1)
                })
                .catch((err) => {
                    console.log(err)
                })
            },

            updateTask (task) {
               axios.put(`/api/tasks/${task.id}`, {
                    completed: task.completed,
                    name: task.name
                })
                .then((res) => {
                    this.fetchData()
                    this.close()
                })
                .catch((err) => {
                    console.log(err)
                })
            },

            done (task) {
                axios.put(`/api/tasks/${task.id}`, {
                    completed: !task.completed,
                    name: task.name
                })
                .then((res) => {
                    console.log('task updated')
                })
                .catch((err) => {
                    console.log(err)
                })
            }
        }
    }   
</script>

<style>
    .tasks-list {
        padding-top: 20px;
    }
    .done label {
        text-decoration: line-through;
    }
</style>
