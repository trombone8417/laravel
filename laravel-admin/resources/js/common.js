import { mapGetters } from 'vuex';
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
        i (desc, title="通知!") {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },
        s (desc, title="成功!") {
            this.$Notice.success({
                title: title,
                desc: desc
            });
        },
        w (desc, title="警告!") {
            this.$Notice.warning({
                title: title,
                desc: desc
            });
        },
        e (desc, title="錯誤") {
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
        },
        checkUserPermission(key){
            if (!this.userPermission) return true;
            // 預設權限為false
            let isPermitted = false;
            for (let d of this.userPermission) {
                if(this.$route.name == d.name){
                    // 判斷權限是否為true
                    if (d[key]) {
                        // 是的話給與權限
                        isPermitted = true;
                        break;
                    }else{
                        break;
                    }
                }
            }
            return isPermitted;
        }
    },

    computed:{
        ...mapGetters({
            'userPermission' : 'getUserPermission'
        }),
        isReadPermitted(){
            return this.checkUserPermission('read');
        },
        isWritePermitted(){
            return this.checkUserPermission('write');
        },
        isUpdatePermitted(){
            return this.checkUserPermission('update');
        },
        isDeletePermitted(){
            return this.checkUserPermission('delete');
        },
    }

};
