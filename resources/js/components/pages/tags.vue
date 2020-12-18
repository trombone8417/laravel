<template>
    <div>
        <div class="content">
			<div class="container-fluid">

				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags&nbsp;<Button><Icon type="md-add" />&nbsp;Add tag</Button></p>

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
							<tr>
								<td>1</td>
								<td class="_table_name">Manhattan's art center "Shed" opening ceremony</td>
								<td>Economy</td>
								<td>
                                    <Button type="info" size="small">Info</Button>
                                    <Button type="error" size="small">Error</Button>
								</td>
							</tr>
						</table>
					</div>
				</div>
                <!-- Modal 7 20:51 -->
                <Modal
                    v-model="addModal"
                    title="Common Modal dialog box title"
                    @on-ok="ok"
                    @on-cancel="cancel">
                    <p>Content of dialog</p>
                    <p>Content of dialog</p>
                    <p>Content of dialog</p>
                </Modal>
			</div>
		</div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            data:{
                tagName:''
            },
            addModal:true
        }
    },
    async created(){
        const res = await this.callApi('post','/app/create_tag',{tagName: 'testtag'});

        if (res.status=200) {

        }else{
            console.log(res)
        }
    }

}
</script>

