<template>
  <div>
    <div class="container-fluid">
      <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
      <div
        class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4"
      >
        <div class="login_header">
          <h1>Login to the dashboard</h1>
        </div>
        <div class="space">
          <Input type="email" v-model="data.email" placeholder="email" />
        </div>
        <div class="space">
          <Input type="password" v-model="data.password" placeholder="******" />
        </div>
        <div class="login_footer">
          <Button
            type="primary"
            @click="login"
            :disabled="isLogging"
            :loading="isLogging"
            >{{ isLogging ? "登入中..." : "登入" }}</Button
          >
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
        email: "",
        password: "",
      },
      isLogging: false,
    };
  },

  methods: {
    async login() {
      if (this.data.email.trim() == "") return this.e("Email不得為空!");
      if (this.data.password.trim() == "") return this.e("Password不得為空!");
      if (this.data.password.length < 6)
        return this.e("Password不得小於6個字元!");
      this.isLogging = true;
      const res = await this.callApi("post", "app/admin_login", this.data);
      if (res.status === 200) {
        this.s(res.data.msg);
        // 若登入成功的話，導回Home頁面
        window.location = "/";
      } else {
        if (res.status === 401) {
          this.e(res.data.msg);
        } else if (res.status === 422) {
          for (let i in res.data.errors) {
            // 格式錯誤提示(後端驗證)
            this.e(res.data.errors[i][0]);
          }
        } else {
          this.swr();
        }
      }
      this.isLogging = false;
    },
  },
};
</script>

<style scoped>
._1adminOverveiw_table_recent {
  margin: 0 auto;
  margin-top: 220px;
}
.login_footer {
  text-align: center;
}
.login_header {
  text-align: center;
  margin-bottom: 25px;
}
</style>
