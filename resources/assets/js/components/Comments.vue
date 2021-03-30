<template>
	<div class="container-fluid mt-3 pt-3">
		<div class="row justify-content-center">
			<div class="col-md-3 mt-3 pt-3">

				<div class="card">
				  <div class="card-header">
				    <h3>Add Comments here</h3>
				  </div>
				  <div class="card-body">

				    <form @submit.prevent="addComment">
						<div class="form-group">
							<input class="form-control" type="text" placeholder="Type Username here..." v-model="username">
							<small class="text-danger">{{error_username}}</small>
						</div>
						<div class="form-group">
							<textarea class="form-control" placeholder="Comment here..." v-model="comment" rows="7"></textarea>
							<small class="text-danger">{{error_comment}}</small>
						</div>
						<button type="submit" class="btn btn-info">Comment</button>
					</form>

				  </div>
				</div>

			</div> <!-- /col -->

			<div class="col-md-6 mt-3 pt-3">
				
				<div class="card">
					<div class="card-header">
					    <h3>View Comments here</h3>
					</div>

					<div class="card-body">
						<div class="row">
							<div class="col-md-12" v-for="comment in comments">
								<div class="card mb-4">
									<div class="card-body">
										<div class="row justify-content-center">
											<div class="col-md-3">
												<img style="width: 50px; height: 50px;" :src="'/images/avatar.png'" alt="Avatar Image">
											</div>
											<div class="col-md-9">
												<strong>{{ comment.username }}</strong><br>
												<small>{{ comment.comment }}</small>
												<br>
												<small style="font-size: 0.5rem;">{{ comment.created_at }}</small>
												<br>

												<div v-show="comment.counter < 3">
													<a class="text-success" href="#" @click.prevent="launchreply = !launchreply">Reply</a>
												</div>

													<small><a href="#" @click.prevent="getReplies(comment.id)">View Replies</a></small>

												<!-- <div v-show="viewreplies" class="mt-2"> -->
													<div v-for="reply in replies">
														<div v-show="comment.id == reply.parent_id && viewreplies" class="card">
															<div class="card-body">
																<div class="row justify-content-center">
																	<div class="col-md-3">
																		<img style="width: 50px; height: 50px;" :src="'/images/avatar.png'" alt="Avatar Image">
																	</div>
																	<div class="col-md-9">
																		<strong>{{ reply.username }}</strong><br>
																		<small>{{ reply.comment }}</small>
																		<br>
																		<small style="font-size: 0.5rem;">{{ reply.created_at }}</small>
																		<br>

																		<div v-show="reply.counter < 3">
																			<a href="#" @click.prevent="launchreply = !launchreply">Reply</a>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												<!-- </div> -->

												<div v-show="launchreply" class="card mt-2">
													<div class="card-body">
														<form @submit.prevent="addReply(comment.id)">
															<!-- <input type="hidden" value="{{ comment.id }}"> -->
															<div class="form-group">
																<input class="form-control" type="text" placeholder="Type Username here..." v-model="username2">
																<small class="text-danger">{{error_username2}}</small>
															</div>
															<div class="form-group">
																<textarea class="form-control" placeholder="Comment here..." v-model="comment2" rows="7"></textarea>
																<small class="text-danger">{{error_comment2}}</small>
															</div>
															<button type="submit" class="btn btn-primary">Reply</button>
														</form>
													</div>
												</div>

											</div> <!-- /col -->

										</div> <!-- /row -->

									</div> <!-- /card body -->
								</div> <!-- /card -->
							</div>
						</div>
						
					</div> <!-- /cardbody -->
				</div> <!-- main card -->
			</div> <!-- /col -->

		</div> <!-- row -->
	</div> <!-- /container -->
</template>

<script>
	export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
              username: '',
              comment: '',
              error_username: '',
              error_comment: '',
              counter: '',

              username2: '',
              comment2: '',
              error_username2: '',
              error_comment2: '',

              comments: [],
              interval: null,
              launchreply: false,
              viewreplies: false,
              replies: []
            };
        },
        created() {
			this.interval = setInterval(this.getComments, 500);
		},

        methods: {
            addComment() {

                let thiss = this;

                axios.post('/addcomment', {
                    username: this.username,
                    comment: this.comment
                })
                .then(function (response) {
                	thiss.username = '';
                	thiss.comment = '';
                    console.log(response.data);
                })
                .catch(function (error) {

                	if (error) {
                		if (error.response.data.errors.username) {
	                		thiss.error_username = error.response.data.errors.username[0];
	                	}
	                    if (error.response.data.errors.comment) {
	                		thiss.error_comment = error.response.data.errors.comment[0];
	                	}
                	}
                	
                    console.log(error.response);
                });
            },
            getComments() {

            	let thiss = this;

				axios.get('/getcomments')
					.then(function (response) {
						thiss.comments = response.data;
		                // console.log(response.data);
		            })
            },
            getReplies(comment_id) {

            	let thiss = this;

				axios.get(`/getreplies/${comment_id}`)
					.then(function (response) {
						thiss.replies = response.data;
						thiss.viewreplies = !thiss.viewreplies;
		                // console.log(response.data);
		            })
            },
            addReply(comment_id) {

            	let thiss = this;

                axios.post(`/reply/${comment_id}`, {
                    username: this.username2,
                    comment: this.comment2
                })
                .then(function (response) {
                	thiss.username2 = '';
                	thiss.comment2 = '';
                    console.log(response.data);
                })
                .catch(function (error) {

                	if (error) {
                		if (error.response.data.errors.username) {
	                		thiss.error_username2 = error.response.data.errors.username[0];
	                	}
	                    if (error.response.data.errors.comment) {
	                		thiss.error_comment2 = error.response.data.errors.comment[0];
	                	}
                	}
                	
                    // console.log(error.response);
                });
             	console.log(comment_id);
            }
        }
    }
</script>