<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div
                    class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
                >
                    <p class="_title0">
                        Tags&nbsp;
                        <Button @click="addModal = true"
                            ><Icon type="md-add" />&nbsp;新增tag</Button
                        >
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Tag name</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- ITEMS -->
                            <tr
                                v-for="(tag, i) in tags"
                                :key="i"
                                v-if="tags.length"
                            >
                                <td>{{ tag.id }}</td>
                                <td class="_table_name">
                                    {{ tag.tagName }}
                                </td>
                                <td>{{ tag.created_at }}</td>
                                <td>
                                    <Button
                                        type="info"
                                        size="small"
                                        @click="showEditModal(tag, i)"
                                        >編輯</Button
                                    >
                                    <Button
                                        type="error"
                                        size="small"
                                        @click="showDeletingModal(tag, i)"
                                        :loading="tag.isDeleting"
                                        >刪除</Button
                                    >
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- 加入tag -->
                <Modal
                    v-model="addModal"
                    title="Add tag"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="data.tagName" placeholder="Add tag name" />
                    <div slot="footer">
                        <Button type="default" @click="addModal = false"
                            >關閉</Button
                        >
                        <Button
                            type="primary"
                            @click="addTag"
                            :disabled="isAdding"
                            :loading="isAdding"
                            >{{ isAdding ? "處理中..." : "新增tag" }}</Button
                        >
                    </div>
                </Modal>
                <!-- 編輯tag -->
                <Modal
                    v-model="editModal"
                    title="Edit tag"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input
                        v-model="editData.tagName"
                        placeholder="Edit tag name"
                    />
                    <div slot="footer">
                        <Button type="default" @click="editModal = false"
                            >關閉</Button
                        >
                        <Button
                            type="primary"
                            @click="editTag"
                            :disabled="isAdding"
                            :loading="isAdding"
                            >{{ isAdding ? "處理中..." : "編輯tag" }}</Button
                        >
                    </div>
                </Modal>
                <!-- 刪除提示 -->
                <delete-modal></delete-modal>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex';
import deleteModal from "../components/deleteModal";
export default {
    data() {
        return {
            data: {
                tagName: ""
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            tags: [],
            editData: {
                tagName: ""
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex: -1,
            websiteSettings: []
        };
    },

    methods: {
        // 新增tag
        async addTag() {
            // 前端驗證
            if (this.data.tagName.trim() == "")
                return this.e("Tag不得為空!");
            const res = await this.callApi("post", "app/create_tag", this.data);
            // 201 新增tag成功
            if (res.status === 201) {
                // 若有新資料加入，加入Array
                this.tags.unshift(res.data);
                this.s("Tag新增成功!");
                // 關閉modal
                this.addModal = false;
                this.data.tagName = "";
            } else {
                // 422新增失敗
                if (res.status == 422) {
                    if (res.data.errors.tagName) {
                        // 顯示錯誤內容
                        this.i(res.data.errors.tagName[0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        async editTag() {
            // 前端驗證
            if (this.editData.tagName.trim() == "")
                return this.e("Tag不得為空!");
            const res = await this.callApi(
                "post",
                "app/edit_tags",
                this.editData
            );
            // 201 新增tag成功
            if (res.status === 200) {
                // 修改TagName的文字
                this.tags[this.index].tagName = this.editData.tagName;
                this.s("Tag has been edited successfully!");
                // 關閉modal
                this.editModal = false;
            } else {
                // 422新增失敗
                if (res.status == 422) {
                    if (res.data.errors.tagName) {
                        // 顯示錯誤內容
                        this.i(res.data.errors.tagName[0]);
                    }
                } else {
                    // 顯示something wrong error
                    // 出現錯誤，請再試一次
                    this.swr();
                }
            }
        },
        showEditModal(tag, index) {
            let obj = {
                id: tag.id,
                tagName: tag.tagName
            };
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },

        showDeletingModal(tag, i) {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/delete_tags",
                data: tag,
                deletingIndex: i,
                isDeleted: false
            };
            this.$store.commit("setDeletingModalObj", deleteModalObj);
            // this.deleteItem = tag
            // this.deletingIndex = i
            // this.showDeleteModal = true
        }
    },

    async created() {
        const res = await this.callApi("get", "app/get_tags");
        if (res.status == 200) {
            this.tags = res.data;
        } else {
            this.swr();
        }
    },
    components: {
        deleteModal
    },
    computed : {
        ...mapGetters(['getDeleteModalObj'])
    },
    watch : {
        getDeleteModalObj(obj){
            if (obj.isDeleted) {
                this.tags.splice(obj.deletingIndex, 1);
            }
        }
    }
};
</script>
