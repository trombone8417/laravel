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
                        <Select
                            v-model="data.id"
                            placeholder="請選擇..."
                            style="width:300px"
                            @on-change="changeAdmin"
                        >
                            <Option
                                :value="r.id"
                                v-for="(r, i) in roles"
                                :key="i"
                                v-if="roles.length"
                                >{{ r.roleName }}</Option
                            >
                        </Select>
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>Resoruce name</th>
                                <th>Read</th>
                                <th>Write</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- ITEMS -->
                            <tr v-for="(r, i) in resources" :key="i">
                                <td>{{ r.resourceName }}</td>
                                <td><Checkbox v-model="r.read"></Checkbox></td>
                                <td><Checkbox v-model="r.write"></Checkbox></td>
                                <td>
                                    <Checkbox v-model="r.update"></Checkbox>
                                </td>
                                <td>
                                    <Checkbox v-model="r.delete"></Checkbox>
                                </td>
                            </tr>
                            <!-- ITEMS -->
                            <div class="center_button">
                                <Button
                                    type="primary"
                                    :loading="isSending"
                                    :disabled="isSending"
                                    @click="assignRoles"
                            v-if="isWritePermitted"
                                    >Assign</Button
                                >
                            </div>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            data: {
                roleName: "",
                id: null
            },
            roles: [],
            isSending: false,
            resources: [
                {
                    resourceName: "Tags",
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: "tags"
                },
                {
                    resourceName: "Category",
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: "category"
                },
                {
                    resourceName: "Admin users",
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: "adminusers"
                },
                {
                    resourceName: "Role",
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: "role"
                },
                {
                    resourceName: "Assign role",
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: "assignRole"
                },
                {
                    resourceName: "Home",
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: "/"
                }
            ],
            defaultResourcesPermission: [
                {
                    resourceName: "Tags",
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: "tags"
                },
                {
                    resourceName: "Category",
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: "category"
                },
                {
                    resourceName: "Admin users",
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: "adminusers"
                },
                {
                    resourceName: "Role",
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: "role"
                },
                {
                    resourceName: "Assign role",
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: "assignRole"
                },
                {
                    resourceName: "Home",
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: "/"
                }
            ]
        };
    },

    methods: {
        // 送出permission資料
        async assignRoles() {
            // 將 Javascript 物件轉為 JSON 字串
            let data = JSON.stringify(this.resources);
            const res = await this.callApi("post", "app/assign_roles", {
                permission: data,
                id: this.data.id
            });
            if (res.status == 200) {
                this.s("Role has been assigned successfully!");
                // 利用id取得index的值
                let index = this.roles.findIndex(
                    role => role.id == this.data.id
                );
                // 更新資料
                this.roles[index].permission = data;
            } else {
                this.swr();
            }
        },
        // 下拉選單，選出要的資料
        changeAdmin() {
            // 利用id取得index的值
            let index = this.roles.findIndex(role => role.id == this.data.id);
            // 撈出idex資料
            let permission = this.roles[index].permission;
            // 若permission沒有資料
            if (!permission) {
                // 帶入預設值，checkbox都是空的
                this.resources = this.defaultResourcesPermission;
            } else {
                // 列出permission資料
                this.resources = JSON.parse(permission);
            }
        }
    },

    async created() {
        // 撈出roles的資料
        const res = await this.callApi("get", "app/get_roles");
        if (res.status == 200) {
            // 接收所有role的資料
            this.roles = res.data;
            if (res.data.length) {
                // Admin ID
                this.data.id = res.data[0].id;
                // 若admin permission有資料的話
                if (res.data[0].permission) {
                    // 撈出admin資料
                    // 接收 JSON 字串，轉為 Javascript 物件
                    this.resources = JSON.parse(res.data[0].permission);
                }
            }
        } else {
            this.swr();
        }
    }
};
</script>
