<template>
    <div>
        <h1 ref="myref">Hook titorial</h1>
        <p>My name is : {{name}}</p>
    </div>
</template>
<script>
export default {
    data(){
        return{
            name:'kuei'
        }
    },

    beforeCreated(){
        console.log(this.name)
    },
    created(){
        console.log("created()"+this.name)
    },
    mounted(){
        console.log(this.$refs.myref)
    }
}
</script>
