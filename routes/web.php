<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['middleware' => ['auth']], function() use ($router){

	$router->get('/hola', ["uses" => "UserController@usuarios"]);
	$router->delete('/user/{id}', ["uses" => "UserController@deleteUser"]);
	$router->put('/user/{id}', ["uses" => "UserController@updateUser"]);

});

$router->get('/user/{id}', ["uses" => "UserController@getUser"]);

//Posts
$router->post('/file', ["uses" => "PostController@uploadFile"]);
$router->post('/createPost', ["uses" => "PostController@createPost"]);
$router->get('/post', ["uses" => "PostController@index"]);
$router->get('/post/{id}', ["uses" => "PostController@getPost"]);
$router->put('/post/{id}', ["uses" => "PostController@updatePost"]);
$router->delete('/post/{id}', ["uses" => "PostController@deletePost"]);
$router->get('/postByUser/{id}', ["uses" => "PostController@getPostByUser"]);
$router->get('/posts', ["uses" => "PostController@getPosts"]);
$router->post('/post', ["uses" => "PostController@createPost"]);

//Comments
$router->get('/testComment', ["uses" => "CommentController@index"]);
$router->post('/comment', ["uses" => "CommentController@createComment"]);
$router->get('/allComments', ["uses" => "CommentController@getComments"]);
$router->get('/comment/{id}', ["uses" => "CommentController@getComment"]);
$router->get('/commentsFromPost/{id}', ["uses" => "CommentController@getCommentsFromPost"]);
$router->get('/commentsByUser/{id}', ["uses" => "CommentController@getCommentsByUser"]);
$router->put('/comment/{id}', ["uses" => "CommentController@updateComment"]);
$router->delete('/comment/{id}', ["uses" => "CommentController@deleteComment"]);

//Likes
$router->get('/testLike', ["uses" => "LikeController@index"]);
$router->post('/postLike', ["uses" => "LikeController@createPostLike"]);
$router->post('/commentLike', ["uses" => "LikeController@createCommentLike"]);
$router->get('/likes', ["uses" => "LikeController@getLikes"]);
$router->get('/like/{id}', ["uses" => "LikeController@getLike"]);
$router->get('/likesFromPost/{id}', ["uses" => "LikeController@getLikesFromPost"]);
$router->delete('/like/{id}', ["uses" => "LikeController@deleteLike"]);

//global
$router->get('/all/{id}', ["uses" => "PostController@postCommentsLikes"]);
$router->post('/tests', ["uses" => "LikeController@forTests"]);


$router->post('/login', ["uses" => "UserController@login"]);

$router->post('/user', ["uses" => "UserController@crearUsuario"]);

$router->post('/laQueQuiera', ["uses" => "UserController@comoQuieran"]);

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/holaMundo', function () {
    return "Hola Mundo";
});

$router->get('/chantal', function () {
    return "vales verga";
});

$router->get('/key', function () {
    return str_random(32);
});


$router->get('/holaController', ["uses" => "ExampleController@index"]);

$router->get('/holaController2', ["uses" => "ExampleController@pepa"]);

//$router->get('/user', ["uses" => "ExampleController@createName"]);

$router->get('/manchego', ["uses" => "ExampleController@createManchego"]);
$router->get('/gente', ["uses" => "ExampleController@gente"]);
