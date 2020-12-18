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

        }
    },
};
