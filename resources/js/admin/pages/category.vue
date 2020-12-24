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
                                <th>Icon image</th>
                                <th>Category name</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- ITEMS -->
                            <tr
                                v-for="(category, i) in categoryLists"
                                :key="i"
                                v-if="categoryLists.length"
                            >
                                <td>{{ category.id }}</td>
                                <td class="table_image">
                                    <img :src="category.iconImage" />
                                </td>
                                <td class="_table_name">
                                    {{ category.categoryName }}
                                </td>
                                <td>{{ category.created_at }}</td>
                                <td>
                                    <Button
                                        type="info"
                                        size="small"
                                        @click="showEditModal(category, i)"
                                        >Edit</Button
                                    >
                                    <Button
                                        type="error"
                                        size="small"
                                        @click="showDeletingModal(category, i)"
                                        :loading="category.isDeleting"
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
                <Input v-model="data.categoryName" placeholder="Add category name" />
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
                            <img :src ="`${data.iconImage}`" />
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
                            @click="addCategory"
                            :disabled="isAdding"
                            :loading="isAdding"
                            >{{ isAdding ? "Adding.." : "Add Category" }}</Button
                        >
                    </div>
                </Modal>
                <!-- 編輯tag -->
                <Modal
                    v-model="editModal"
                    title="Edit category"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="editData.categoryName" placeholder="Add category name" />
                <div class="space"></div>
                <!-- 上傳圖片 -->
                    <Upload v-show="isIconImageNew"
                        ref="editDataUploads"
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
                    <div class="demo-upload-list" v-if="editData.iconImage">
                            <!-- 上傳圖片，顯示圖片 -->
                            <img :src ="`${editData.iconImage}`" />
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
                            </div>
                    </div>
                    <div slot="footer">
                        <Button type="default" @click="closeEditModal"
                            >Close</Button
                        >
                        <Button
                            type="primary"
                            @click="editCategory"
                            :disabled="isAdding"
                            :loading="isAdding"
                            >{{ isAdding ? "Editing.." : "Edit Category" }}</Button
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
            // 新增的彈出視窗(預設關閉)
            addModal: false,
            // 編輯的彈出視窗(預設關閉)
            editModal: false,
            isAdding: false,
            categoryLists: [],
            editData: {
                iconImage:'',
                categoryName:''
            },
            index: -1,
            // 刪除的警示視窗(預設關閉)
            showDeleteModal: false,
            isDeleting: false,
            deleteItem:{},
            deletingIndex:-1,
            token:'',
            isIconImageNew:false,
            isEditingItem:false,
        };
    },

    methods: {
        async addCategory() {
            // 前端驗證
            if (this.data.categoryName.trim() == '')
                return this.e("Category name is required");
            if (this.data.iconImage.trim() == '')
                return this.e("Icon image name is required");
            // 圖片位置
            this.data.iconImage = `/uploads/${this.data.iconImage}`
            const res = await this.callApi("post", "app/create_category", this.data);
            // 201 新增category成功
            if (res.status === 201) {
                // 若有新資料加入，加入Array
                this.categoryLists.unshift(res.data);
                this.s("Category has been added successfully!");
                // 關閉modal
                this.addModal = false;
                this.data.categoryName = '';
                this.data.iconImage = '';
            } else {
                if (res.status == 422) {
                    if (res.data.errors.categoryName) {
                        this.i(res.data.errors.categoryName[0]);
                    }
                    if (res.data.errors.iconImage) {
                        this.i(res.data.errors.iconImage[0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        async editCategory() {
            // 前端驗證
            if (this.editData.categoryName.trim() == '')
                return this.e("Category name is required");
            if (this.editData.iconImage.trim() == '')
                return this.e("Icon image name is required");
            const res = await this.callApi(
                "post",
                "app/edit_category",
                this.editData
            );
            // 201 新增tag成功
            if (res.status === 200) {
                // 修改TagName的文字
                this.categoryLists[this.index].categoryName = this.editData.categoryName;
                this.s("Tag has been edited successfully!");
                // 關閉modal
                this.editModal = false;
            } else {
                if (res.status == 422) {
                    // 顯示錯誤
                    if (res.data.errors.categoryName) {
                        this.i(res.data.errors.categoryName[0]);
                    }
                    if (res.data.errors.iconImage) {
                        this.i(res.data.errors.iconImage[0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        showEditModal(category, index) {
            // let obj = {
            //     id: tag.id,
            //     tagName: tag.tagName
            // };
            console.log(category)
            this.editData = category;
            this.editModal = true;
            this.index = index;
            this.isEditingItem = true
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
        // 設置成功
        handleSuccess (res, file) {
            res = `/uploads/${res}`
            if (this.isEditingItem) {
                return this.editData.iconImage = res
            }
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
        async deleteImage(isAdd=true){
            let image
            // 編輯圖片
            if (!isAdd) {
                this.isIconImageNew = true
                image = this.editData.iconImage
                this.editData.iconImage = ''
                // 刪除檔名
                this.$refs.editDataUploads.clearFiles()
            }else{
                image = this.data.iconImage
                this.data.iconImage = ''
                // 刪除檔名
                this.$refs.uploads.clearFiles()
            }
       
            const res = await this.callApi('post', 'app/delete_image', {imageName: image})
            if (res.status!=200) {
                this.data.iconImage = image
                this.swr()
            }
        },

        closeEditModal(){
            this.isEditingItem= false
            this.editModal = false
        }

    },

    async created() {
        this.token = window.Laravel.csrfToken
        const res = await this.callApi("get", "app/get_category");
        if (res.status == 200) {
            this.categoryLists = res.data;
        } else {
            this.swr();
        }
    },

};
</script>
