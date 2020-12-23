<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div
                    class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
                >
                    <p class="_title0">
                        Category&nbsp;
                        <Button @click="addModal = true"
                            ><Icon type="md-add" />&nbsp;Add Category</Button
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
                                        >Edit</Button
                                    >
                                    <Button
                                        type="error"
                                        size="small"
                                        @click="showDeletingModal(tag, i)"
                                        :loading="tag.isDeleting"
                                        >Delete</Button
                                    >
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- 加入tag -->
                <Modal
                    v-model="addModal"
                    title="Add category"
                    :mask-closable="false"
                    :closable="false"
                >
                <Input v-model="data.tagName" placeholder="Add category name" />
                <div class="space"></div>
                <!-- 上傳圖片 -->
                    <Upload
                        ref="uploads"
                        type="drag"
                        :headers="{'x-csrf-token':token, 'X-Requested-With': 'XMLHttpRequest'}"
                        :on-success="handleSuccess"
                        :format="['jpg','jpeg','png']"
                        :on-error="handleError"
                        :max-size="2048"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        action="/app/upload">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>

                        <div class="demo-upload-list" v-if="data.iconImage">
                            <!-- 上傳圖片，顯示圖片 -->
                            <img :src ="`/uploads/${data.iconImage}`" />
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
                            </div>
                        </div>
                    <div slot="footer">
                        <Button type="default" @click="addModal = false"
                            >Close</Button
                        >
                        <Button
                            type="primary"
                            @click="addTag"
                            :disabled="isAdding"
                            :loading="isAdding"
                            >{{ isAdding ? "Adding.." : "Add tag" }}</Button
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
                            >Close</Button
                        >
                        <Button
                            type="primary"
                            @click="editTag"
                            :disabled="isAdding"
                            :loading="isAdding"
                            >{{ isAdding ? "Editing.." : "Edit tag" }}</Button
                        >
                    </div>
                </Modal>
                <!-- 確認是否刪除對話框 -->
                <Modal v-model="showDeleteModal" width="360">
                    <p slot="header" style="color:#f60;text-align:center">
                        <Icon type="ios-information-circle"></Icon>
                        <span>Delete confirmation</span>
                    </p>
                    <div style="text-align:center">
                        <p>
                            確定要刪除嗎?
                        </p>
                    </div>
                    <div slot="footer">
                        <Button
                            type="error"
                            size="large"
                            long
                            :loading="isDeleting"
                            :disabled="isDeleting"
                            @click="deleteTag"
                            >Delete</Button
                        >
                    </div>
                </Modal>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            data: {
                iconImage:'',
                categoryName:''
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            tags: [],
            editData: {
                tagName: ''
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem:{},
            deletingIndex:-1,
            token:''
        };
    },

    methods: {
        async addTag() {
            // 前端驗證
            if (this.data.tagName.trim() == "")
                return this.e("Tag name is required");
            const res = await this.callApi("post", "app/create_tag", this.data);
            // 201 新增tag成功
            if (res.status === 201) {
                // 若有新資料加入，加入Array
                this.tags.unshift(res.data);
                this.s("Tag has been added successfully!");
                // 關閉modal
                this.addModal = false;
                this.data.tagName = "";
            } else {
                if (res.status == 422) {
                    if (res.data.errors.tagName) {
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
                return this.e("Tag name is required");
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
                if (res.status == 422) {
                    if (res.data.errors.tagName) {
                        this.i(res.data.errors.tagName[0]);
                    }
                } else {
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
        async deleteTag() {
            this.isDeleting = true
            const res = await this.callApi("post", "app/delete_tags", this.deleteItem);
            if (res.status === 200) {
                this.tags.splice(this.deletingIndex, 1);
                this.s("Tag has been deleted successfully!");
            } else {
                this.swr();
            }
            this.isDeleting = false
            this.showDeleteModal = false
        },
        showDeletingModal(tag,i){
            this.deleteItem = tag
            this.deletingIndex = i
            this.showDeleteModal = true
        },
        handleSuccess (res, file) {
            this.data.iconImage = res
        },
        handleError (res, file) {
            // 通知格式錯誤或其他錯誤
            this.$Notice.warning({
                title: '檔案格式不正確',
                // 巢狀樣板
                desc: `${file.errors.file.length ? file.errors.file[0] : '其他錯誤'}`
            });
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
            });
        },
        // 超過檔案大小提示
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
        },
        // 上傳圖案，刪除
        async deleteImage(){
            let image = this.data.iconImage
            this.data.iconImage = ''
            // 刪除檔名
            this.$refs.uploads.clearFiles()
            const res = await this.callApi('post', 'app/delete_image', {imageName: image})
            if (res.status!=200) {
                this.data.iconImage = image
                this.swr()
            }
        }

    },

    async created() {
        this.token = window.Laravel.csrfToken
        const res = await this.callApi("get", "app/get_tags");
        if (res.status == 200) {
            this.tags = res.data;
        } else {
            this.swr();
        }
    }
};
</script>
