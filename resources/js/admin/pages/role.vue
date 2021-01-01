<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div
                    class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
                >
                    <p class="_title0">
                        角色&nbsp;
                        <Button @click="addModal = true"
                            ><Icon type="md-add" />&nbsp;新增角色</Button
                        >
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Role Type</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- ITEMS -->
                            <tr
                                v-for="(role, i) in roles"
                                :key="i"
                                v-if="roles.length"
                            >
                                <td>{{ role.id }}</td>
                                <td class="_table_name">
                                    {{ role.roleName }}
                                </td>
                                <td>{{ role.created_at }}</td>
                                <td>
                                    <Button
                                        type="info"
                                        size="small"
                                        @click="showEditModal(role, i)"
                                        >編輯</Button
                                    >
                                    <Button
                                        type="error"
                                        size="small"
                                        @click="showDeletingModal(role, i)"
                                        :loading="role.isDeleting"
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
                    title="Add role"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="data.roleName" placeholder="Add role name" />
                    <div slot="footer">
                        <Button type="default" @click="addModal = false"
                            >關閉</Button
                        >
                        <Button
                            type="primary"
                            @click="add"
                            :disabled="isAdding"
                            :loading="isAdding"
                            >{{ isAdding ? "處理中..." : "新增role" }}</Button
                        >
                    </div>
                </Modal>
                <!-- 編輯tag -->
                <Modal
                    v-model="editModal"
                    title="編輯角色"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input
                        v-model="editData.roleName"
                        placeholder="Edit role name"
                    />
                    <div slot="footer">
                        <Button type="default" @click="editModal = false"
                            >關閉</Button
                        >
                        <Button
                            type="primary"
                            @click="edit"
                            :disabled="isAdding"
                            :loading="isAdding"
                            >{{ isAdding ? "處理中..." : "編輯角色" }}</Button
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
                roleName: ""
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            roles: [],
            editData: {
                roleName: ""
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
        // 新增role
        async add() {
            // 前端驗證
            if (this.data.roleName.trim() == "")
                return this.e("Role不得為空!");
            const res = await this.callApi("post", "app/create_role", this.data);
            // 201 新增tag成功
            if (res.status === 201) {
                // 若有新資料加入，加入Array
                this.roles.unshift(res.data);
                this.s("角色新增成功!");
                // 關閉modal
                this.addModal = false;
                this.data.roleName = "";
            } else {
                // 422新增失敗
                if (res.status == 422) {
                    if (res.data.errors.roleName) {
                        // 顯示錯誤內容
                        this.i(res.data.errors.roleName[0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        async edit() {
            // 前端驗證
            if (this.editData.roleName.trim() == "")
                return this.e("role不得為空!");
            const res = await this.callApi(
                "post",
                "app/edit_role",
                this.editData
            );
            // 201 新增tag成功
            if (res.status === 200) {
                // 修改TagName的文字
                this.roles[this.index].roleName = this.editData.roleName;
                this.s("角色編輯成功!");
                // 關閉modal
                this.editModal = false;
            } else {
                // 422新增失敗
                if (res.status == 422) {
                    if (res.data.errors.roleName) {
                        // 顯示錯誤內容
                        this.i(res.data.errors.roleName[0]);
                    }
                } else {
                    // 顯示something wrong error
                    // 出現錯誤，請再試一次
                    this.swr();
                }
            }
        },
        showEditModal(role, index) {
            let obj = {
                id: role.id,
                roleName: role.roleName
            };
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },

        showDeletingModal(role, i) {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/delete_role",
                data: role,
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
        const res = await this.callApi("get", "app/get_roles");
        if (res.status == 200) {
            this.roles = res.data;
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
                this.roles.splice(obj.deletingIndex, 1);
            }
        }
    }
};
</script>
