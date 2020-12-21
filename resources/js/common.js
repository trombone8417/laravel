export default {
    data() {
        return {
            data:{
                tagName:''
            }
        };
    },
    methods: {
        async callApi(method, url, dataObj) {
            // Send a POST request
            try {
                return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                });
            } catch (e) {
                return e.response;
            }


        },
        i (desc, title="Hey!") {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },
        s (desc, title="Great!") {
            this.$Notice.success({
                title: title,
                desc: desc
            });
        },
        w (desc, title="Oops!") {
            this.$Notice.warning({
                title: title,
                desc: desc
            });
        },
        e (desc, title="Hey") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        swr (desc="出現錯誤，請再試一次", title="Oops") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        }
    },

};
