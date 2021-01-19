<template>
    <div>
        <h1>the number is : {{ view }}</h1>
        <button @click="updateCounter(1)">加一</button>
        <button @click="updateCounter(-1)">減一</button>
        <hr />
        <div v-for="(blog, i) in blogs" :key="i">
            <h1>{{ blog.title }}</h1>
            <p>{{ blog.post }}</p>
        </div>
        <hr />
        <div>
            <h1 v-if="showItem"> show item if true</h1>
            <h1 v-else>show item if false</h1>
        </div>
        <button @click="ChangeShowItem">Change showItem</button>
    </div>
</template>
<script>
export default {
    data() {
        return {
            view: 0,
            blogs: [],
            showItem : false
        };
    },
    methods: {
        updateCounter(number) {
            this.view += number;
        },
        ChangeShowItem(){
            this.showItem=!this.showItem
        }
    },
    created() {
        this.view = 100;
        let posts = [
            { title: "標題1", post: "this is the blog post1", id: 1 },
            { title: "標題2", post: "this is the blog post2", id: 2 },
            { title: "標題3", post: "this is the blog post3", id: 3 },
            { title: "標題4", post: "this is the blog post4", id: 4 }
        ];
        this.blogs = posts;
    }
};
</script>
