<template>
    <div>
        <!-- 確認是否刪除對話框(共用) -->
        <Modal
            :value="getDeleteModalObj.showDeleteModal"
            :mask-closable="false"
            :closable="false"
            width="360"
        >
            <p slot="header" style="color:#f60;text-align:center">
                <Icon type="ios-information-circle"></Icon>
                <span>刪除確認?</span>
            </p>
            <div style="text-align:center">
                <p>
                    確定要刪除嗎?
                </p>
            </div>
            <div slot="footer">
                <Button
                    type="default"
                    size="large"
                    @click="closeModal"
                    >關閉</Button
                >
                <Button
                    type="error"
                    size="large"
                    :loading="isDeleting"
                    :disabled="isDeleting"
                    @click="deleteTag"
                    >刪除</Button
                >
            </div>
        </Modal>
    </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
    data() {
        return {
            isDeleting: false
        };
    },
    methods: {
        async deleteTag() {
            this.isDeleting = true;
            const res = await this.callApi(
                // HTTP方式
                "post",
                // 刪除Url
                this.getDeleteModalObj.deleteUrl,
                this.getDeleteModalObj.data
            );
            if (res.status === 200) {
                this.s('刪除成功!');
                // 進行刪除
                this.$store.commit('setDeleteModal', true);
            } else {
                this.swr();
                // 取消刪除
                this.$store.commit('setDeleteModal', false);
            }
            this.isDeleting = false
        },
        // 關閉對話框
        closeModal(){
            // 取消刪除
            this.$store.commit('setDeleteModal', false);
        }
    },
    computed: {
        ...mapGetters(["getDeleteModalObj"])
    }
};
</script>
