<template>
    <div class="container overflow-auto flex-nowrap float-none">
        <div class="row flex-nowrap" id="board">
            <draggable element="div" v-model="columns" :options="dragOptions">
                <transition-group>
                    <div class="col-xs-4" v-for="(element,index) in columns" :key="element.id">
                        <div class="card">
                            <div class="card-header card-title">
                                <textarea v-if="element===editingColumn" class="text-input" @blur="endEditingColumn(element)" @keyup.enter="endEditingColumn(element)" v-model="element.name"></textarea>
                                <label for="checkbox" v-if="element!== editingColumn" @dblclick="editColumn(element)">{{element.name}}</label>
                                <button v-if="element!== editingColumn" type="button" class="close" aria-label="Close" @click="deleteColumn(element, index)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="card-body card-body-dark">
                                <draggable :options="dragOptions" element="div" v-model="element.tasks" @end="changeColumn">
                                    <transition-group :id="element.id">
                                        <div v-for="(task,index) in element.tasks" :key="task.id" class="transit-1" :id="task.id">
                                            <div class="small-card">
                                                <textarea v-if="task===editingTask" class="text-input" @blur="endEditing(task)" @keyup.enter="endEditing(task)" v-model="task.task"></textarea>
                                                <label for="checkbox" v-if="task!== editingTask" @dblclick="editTask(task)">{{task.task}}</label>
                                                <button v-if="task!== editingTask" type="button" class="close" aria-label="Close" @click="deleteTask(task, element, index)">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                    </transition-group>
                                </draggable>
                                <div class="small-card">
                                    <h5 class="text-center" v-on:click="addNew(index)">Add new card</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition-group>
            </draggable>
            <div class="text-center col-xs-4">
                <button type="button" class="btn btn-info" @click="addNewColumn()">Add new column</button>
            </div>
        </div>
    </div>
</template>

<style scoped>

body, html{
    height: 100%;
}

.card {
    border:0;
    border-radius: 0.5rem;
    height: 100%;
}
.transit-1 {
    transition: all 1s;
}
.small-card {
    padding: 1rem;
    background: #f5f8fa;
    margin-bottom: 5px;
    border-radius: .25rem;
}
.card-body-dark{
    background-color: #ccc;
}
textarea {
    overflow: visible;
    outline: 1px dashed black;
    border: 0;
    padding: 6px 0 2px 8px;
    width: 100%;
    height: 100%;
    resize: none;
}

</style>

<script>
import draggable from 'vuedraggable'
export default {
    // components:{
    //     draggable
    // },
    el: "#board",
    data(){
        return {
            columns:[],
            editingTask:null,
            editingColumn:null
        }
    },

    methods:{
        addNew(id){
            let task = "New task"
            let column_id = this.columns[id].id

            axios.post('api/task', {task, column_id}).then(response=>{
                this.columns[id].tasks.push(response.data.data)
            })
        },
        deleteTask(task, column, index){
            axios.delete('api/task/'+task.id).then(response=>{
                column.tasks.splice(index, 1)
            })
        },

        loadTasks(){
            this.columns.map(column => {
                axios.get("api/column/"+column.id+"/tasks").then(response=>{
                    column.tasks = response.data
                });
            })
        },
        changeColumn(data){
            let toTask = data.to
            let fromTask = data.from
            let task_id = data.item.id
            let column_id = toTask.id

            axios.put("api/task/"+task_id, {column_id}).then(response=>{
                //
            })
        },
        endEditing(task){
            this.editingTask = null
            axios.put('api/task/'+task.id, {task: task.task}).then(response=>{
                //
            })
        },
        editTask(task){
            this.editingTask = task
        },
        endEditingColumn(column){
            this.editingColumn = null
            axios.put('api/column/'+column.id, {name: column.name}).then(response=>{
                //
            })
        },
        editColumn(column){
            this.editingColumn = column
        },

        deleteColumn(column, index){
            axios.delete('api/column/'+column.id).then(response=>{
                this.columns.splice(index, 1)
            })
        },
        addNewColumn(){
            let column = "New column";
            axios.post('api/column', {name: column}).then(response=>{
                this.columns.push()
            });
        }
    },

    mounted() {
        let token = localStorage.getItem('jwt')
        axios.defaults.headers.common['Content-Type'] = 'application/json'
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
        axios.get('api/column').then(response => {
            response.data.forEach((data) => {
                this.columns.push({
                    id : data.id,
                    name : data.name,
                    tasks : []
                })
            })
            this.loadTasks()
        })
    },
    computed: {
        dragOptions () {
            return  {
                animation: 1,
                group: 'description',
                ghostClass: 'ghost'
            };
        },
    },
    beforeRouteEnter (to, from, next) {
        if ( ! localStorage.getItem('jwt')) {
            return next('login')
        }
        next()
    }
}

</script>
