<template>
    <div class="container-fluid">
        <draggable element="div" class="row flex-nowrap flex-row" id="main-row" v-model="columns" :options="dragOptions">
            <transition-group class="row flex-nowrap">
                <div class="col-4" v-for="(element,index) in columns" :key="element.id">
                    <div class="card">
                        <div class="card-header">
                            <textarea v-if="element===editingColumn" class="text-input" @blur="endEditingColumn(element)" @keyup.enter="endEditingColumn(element)" v-model="element.name"></textarea>
                            <label for="checkbox" v-if="element!== editingColumn" @dblclick="editColumn(element)">{{element.name}}</label>
                            <button v-if="element!== editingColumn" type="button" class="btn-close" aria-label="Close" @click="deleteColumn(element, index)"></button>
                        </div>
                        <div class="card-body">
                            <draggable :options="dragOptions" element="div" v-model="element.tasks" @end="changeColumn">
                                <transition-group :id="element.id">
                                    <div v-for="(task,index) in element.tasks" :key="task.id" class="transit-1" :id="task.id">
                                        <div class="column-items">
                                            <textarea v-if="task===editingTask" class="text-input" @blur="endEditing(task)" @keyup.enter="endEditing(task)" v-model="task.task"></textarea>
                                            <label for="checkbox" v-if="task!== editingTask" @dblclick="editTask(task)">{{task.task}}</label>
                                            <button v-if="task!== editingTask" type="button" class="btn-close" aria-label="Close" @click="deleteTask(task, element, index)"></button>
                                        </div>
                                    </div>
                                </transition-group>
                            </draggable>
                            <div>
                                <h5 class="text-center" id="addCard" v-on:click="addNew(index)">Add new card</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </transition-group>
            <div class="col-4 button-col">
                <button type="button" id="button" @click="addNewColumn()" class="btn btn-info">Add new column</button>
            </div>
        </draggable>
    </div>
</template>

<style scoped>
.transit-1 {
    transition: all 1s;
}
.column-items{
    padding: 1rem;
    background: #f5f8fa;
    margin-bottom: 5px;
    border-radius: .25rem;
}

#addCard:hover{
    cursor: pointer;
}

#addCard{
    padding: 1rem;
    margin-bottom: 5px;
}

.btn-close{
    float: right;
}

#main-row{
    height: 100vh;
    overflow-x: auto;
    overflow-y: hidden;
    white-space: nowrap;
}

.col-4{
    display: inline-block;
}

.button-col{
    float: right;
    width: fit-content;
}

</style>

<script>
import draggable from 'vuedraggable'
export default {
    components:{
        draggable
    },
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
            let columnName = "New column";
            axios.post('api/column', {name: columnName}).then(response=>{
                this.columns.push(response.data.data)
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
