<?php

class BlogPostController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			// 'postOnly + delete', // we only allow deletion via POST request
		);
	}
	public function actionIndex()
    {
		$dataProvider = new CActiveDataProvider('BlogPost', array(
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        $this->render('index', array('dataProvider' => $dataProvider));
        // Fetch all blog posts
        // $blogPosts = BlogPost::model()->findAll();

        // // Render the view and pass the blog posts
        // $this->render('index', array('blogPosts' => $blogPosts));
    }
// 	public function actionSearch()
// {
//     $searchQuery = Yii::app()->request->getParam('search', '');
    
//     $criteria = new CDbCriteria();
//     $criteria->addSearchCondition('title', $searchQuery);
//     $criteria->addSearchCondition('content', $searchQuery, true, 'OR');
    
//     $posts = BlogPost::model()->findAll($criteria);
    
//     $data = [];
//     foreach ($posts as $post) {
//         $data[] = [
//             'id' => $post->id,
//             'title' => $post->title,
//             'content' => $post->content,
//             'author' => $post->user->username,
//             'created_at' => $post->created_at,
//             'likes' => $post->likes,
//         ];
//     }
    
//     echo CJSON::encode($data);
//     Yii::app()->end();
// }
public function actionSearch()
{
    $search = Yii::app()->request->getParam('search', '');
	
    $criteria = new CDbCriteria();
    if ($search) {
		$criteria->with = ['user']; // Assuming 'user' is the relation name in BlogPost model
        $criteria->addSearchCondition('t.title', $search, true, 'OR');
        $criteria->addSearchCondition('t.content', $search, true, 'OR');
		$criteria->addSearchCondition('t.created_at', $search, true, 'OR');
        $criteria->addSearchCondition('t.updated_at', $search, true, 'OR');
		
        $criteria->addSearchCondition('user.username', $search, true, 'OR'); // Adjust 'username' to the correct attribute in the User model
    }
	// if ($authorName) {
       
    // }
    $posts = BlogPost::model()->findAll($criteria);

    $userId = Yii::app()->user->id; // Current logged-in user ID
    $result = [];
    foreach ($posts as $post) {
        $liked = Like::model()->exists('user_id=:userId AND blog_post_id=:postId', [
            ':userId' => $userId,
            ':postId' => $post->id
        ]);
        $postData = $post->attributes;
		$postData['likes']=$post->likes;
		$postData['user']=$post->user;
        $postData['user_liked'] = $liked;
        $result[] = $postData;
    }

    echo CJSON::encode($result);
    Yii::app()->end();
}

// public function actionLike($id)
// {
//     if (Yii::app()->user->isGuest || !Yii::app()->user->isVerified) {
//         throw new CHttpException(403, 'You are not authorized to perform this action.');
//     }

//     $post = BlogPost::model()->findByPk($id);
//     if ($post === null) {
//         throw new CHttpException(404, 'The requested post does not exist.');
//     }

//     $post->likes += 1; // Assuming you have a 'likes' column in your 'blog_post' table
//     if ($post->save()) {
//         echo CJSON::encode(['success' => true, 'likes' => $post->likes]);
//     } else {
//         echo CJSON::encode(['success' => false]);
//     }
//     Yii::app()->end();
// }
	public function actionGetPost($id)
{
    $post = BlogPost::model()->findByPk($id);
    if ($post === null) {
        throw new CHttpException(404, 'The requested post does not exist.');
    }

    $data = array(
        'id' => $post->id,
        'title' => $post->title,
        'content' => $post->content,
        'author' => $post->user->username,
        'created_at' => $post->created_at,
    );

    echo CJSON::encode($data);
    Yii::app()->end();
}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	// public function accessRules()
	// {
	// 	return array(
	// 		array('allow',  // allow all users to perform 'index' and 'view' actions
	// 			'actions'=>array('index','view'),
	// 			'users'=>array('*'),
	// 		),
	// 		array('allow', // allow authenticated user to perform 'create' and 'update' actions
	// 			'actions'=>array('create','update'),
	// 			'users'=>array('@'),
	// 		),
	// 		array('allow', // allow admin user to perform 'admin' and 'delete' actions
	// 			'actions'=>array('admin','delete'),
	// 			'users'=>array('admin'),
	// 		),
	// 		array('deny',  // deny all users
	// 			'users'=>array('*'),
	// 		),
	// 	);
	// }
	public function accessRules() {
		return array(
			array('allow',
				'actions'=>array('index','view','list','search','like','delete'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('create','update','delete'),
				'users'=>array('@'),
				'expression'=>'Yii::app()->user->status == 1', // Allow only verified users
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	// public function actionCreate()
	// {
	// 	$model=new BlogPost;

	// 	// Uncomment the following line if AJAX validation is needed
	// 	// $this->performAjaxValidation($model);

	// 	if(isset($_POST['BlogPost']))
	// 	{
	// 		$model->attributes=$_POST['BlogPost'];
	// 		if($model->save())
	// 			$this->redirect(array('view','id'=>$model->id));
	// 	}

	// 	$this->render('create',array(
	// 		'model'=>$model,
	// 	));
	// }
	public function actionCreate() {
		$model = new BlogPost;
	
		if (isset($_POST['BlogPost'])) {
			$model->attributes = $_POST['BlogPost'];
			$model->user_id = Yii::app()->user->id;
	
			if ($model->save()) {
				$this->redirect(array('view','id'=>$model->id));
			}
		}
	
		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	// public function actionUpdate($id) {
	// 	$model = $this->loadModel($id);
	
	// 	if (isset($_POST['BlogPost'])) {
	// 		$model->attributes = $_POST['BlogPost'];
	// 		if ($model->save()) {
	// 			$this->redirect(array('view','id'=>$model->id));
	// 		}
	// 	}
	
	// 	$this->render('update',array(
	// 		'model'=>$model,
	// 	));
	// }
	public function actionUpdate($id)
{
    $model = $this->loadModel($id);

    if (isset($_POST['BlogPost'])) {
        $model->attributes = $_POST['BlogPost'];
        if ($model->save()) {
            if (Yii::app()->request->isAjaxRequest) {
                echo json_encode(['success' => true]);
                Yii::app()->end();
            } else {
                $this->redirect(array('index'));
            }
        }
    }

    if (Yii::app()->request->isAjaxRequest) {
        $this->renderPartial('_update', array('model' => $model));
        Yii::app()->end();
    } else {
        $this->render('update', array('model' => $model));
    }
}
public function actionList()
{
    $criteria = new CDbCriteria();
    $criteria->condition = 'is_public=1'; // Add any necessary conditions

    $blogPosts = BlogPost::model()->findAll($criteria);

    $data = array();
    foreach ($blogPosts as $post) {
        $data[] = array(
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'author' => $post->user->username,
            'created_at' => $post->created_at,
        );
    }

    echo CJSON::encode($data);
    Yii::app()->end();
}

	
	// public function actionDelete($id) {
	// 	if (Yii::app()->request->isPostRequest) {
	// 		// we only allow deletion via POST request
	// 		$this->loadModel($id)->delete();
	
	// 		if (!isset($_GET['ajax'])) {
	// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	// 		}
	// 	} else {
	// 		throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	// 	}
	// }
	public function actionDelete($id)
{
    $model = $this->loadModel($id);

    // Check if the current user is authorized to delete this post
    if ($model->user_id == Yii::app()->user->id) {
        $model->delete();
        Yii::app()->user->setFlash('success', 'Post deleted successfully.');
    } else {
        throw new CHttpException(403, 'You are not authorized to perform this action.');
    }

    $this->redirect(array('index')); // Redirect to post list page
}

	
	public function loadModel($id) {
		$model = BlogPost::model()->findByPk($id);
		if ($model === null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		if ($model->user_id !== Yii::app()->user->id) {
			throw new CHttpException(403,'You are not authorized to perform this action.');
		}
		return $model;
	}
	

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	// public function actionUpdate($id)
	// {
	// 	$model=$this->loadModel($id);

	// 	// Uncomment the following line if AJAX validation is needed
	// 	// $this->performAjaxValidation($model);

	// 	if(isset($_POST['BlogPost']))
	// 	{
	// 		$model->attributes=$_POST['BlogPost'];
	// 		if($model->save())
	// 			$this->redirect(array('view','id'=>$model->id));
	// 	}

	// 	$this->render('update',array(
	// 		'model'=>$model,
	// 	));
	// }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	// public function actionDelete($id)
	// {
	// 	$this->loadModel($id)->delete();

	// 	// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
	// 	if(!isset($_GET['ajax']))
	// 		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	// }

	/**
	 * Lists all models.
	 */
	// public function actionIndex()
	// {
	// 	$dataProvider=new CActiveDataProvider('BlogPost');
	// 	$this->render('index',array(
	// 		'dataProvider'=>$dataProvider,
	// 	));
	// }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new BlogPost('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BlogPost']))
			$model->attributes=$_GET['BlogPost'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	// public function actionLike($id) {
	// 	$model = new Like;
	// 	$model->user_id = Yii::app()->user->id;
	// 	$model->blog_post_id = $id;
	
	// 	if ($model->save()) {
	// 		$this->redirect(array('view', 'id' => $id));
	// 	} else {
	// 		Yii::app()->user->setFlash('error', 'Unable to like this post.');
	// 		$this->redirect(array('view', 'id' => $id));
	// 	}
	// }
// 	public function actionLike($id)
// {
//     if (Yii::app()->user->isGuest || !Yii::app()->user->isVerified) {
//         throw new CHttpException(403, 'You are not authorized to perform this action.');
//     }

//     $userId = Yii::app()->user->id;
//     $post = BlogPost::model()->findByPk($id);
//     if ($post === null) {
//         throw new CHttpException(404, 'The requested post does not exist.');
//     }

//     $existingLike = Like::model()->findByAttributes(array('user_id' => $userId, 'blog_post_id' => $id));
//     if ($existingLike !== null) {
//         echo CJSON::encode(['success' => false, 'message' => 'You have already liked this post.']);
//         Yii::app()->end();
//     }

//     // Add a like record
//     $like = new Like();
//     $like->user_id = $userId;
//     $like->blog_post_id = $id;
//     if ($like->save()) {
//         $post->likes += 1;
//         $post->save();
//         echo CJSON::encode(['success' => true, 'likes' => $post->likes]);
//     } else {
//         echo CJSON::encode(['success' => false]);
//     }
//     Yii::app()->end();
// }
// public function actionLike($id)
// {
//     if (Yii::app()->user->isGuest || !Yii::app()->user->isVerified) {
//         throw new CHttpException(403, 'You are not authorized to perform this action.');
//     }

//     $userId = Yii::app()->user->id;
//     $post = BlogPost::model()->findByPk($id);
//     if ($post === null) {
//         throw new CHttpException(404, 'The requested post does not exist.');
//     }

//     $existingLike = Like::model()->findByAttributes(array('user_id' => $userId, 'blog_post_id' => $id));
//     if ($existingLike !== null) {
//         // Unlike the post
//         if ($existingLike->delete()) {
//             $post->likes -= 1;
//             $post->save();
//             echo CJSON::encode(['success' => true, 'likes' => $post->likes, 'liked' => false]);
//         } else {
//             echo CJSON::encode(['success' => false]);
//         }
//     } else {
//         // Like the post
//         $like = new Like();
//         $like->user_id = $userId;
//         $like->blog_post_id = $id;
//         if ($like->save()) {
//             $post->likes += 1;
//             $post->save();
//             echo CJSON::encode(['success' => true, 'likes' => $post->likes, 'liked' => true]);
//         } else {
//             echo CJSON::encode(['success' => false]);
//         }
//     }

//     Yii::app()->end();
// }


// 	public function actionLike($id)
// {
//     $post = BlogPost::model()->findByPk($id);
//     if ($post === null) {
//         throw new CHttpException(404, 'The requested post does not exist.');
//     }

//     $post->likes += 1; // Assuming you have a 'likes' column in your 'blog_post' table
//     if ($post->save()) {
//         echo CJSON::encode(['success' => true, 'likes' => $post->likes]);
//     } else {
//         echo CJSON::encode(['success' => false]);
//     }
//     Yii::app()->end();
// }
public function actionLike($id)
{
    if (Yii::app()->user->isGuest || !Yii::app()->user->isVerified) {
        throw new CHttpException(403, 'You are not authorized to perform this action.');
    }

    $userId = Yii::app()->user->id;
    $post = BlogPost::model()->findByPk($id);
    if ($post === null) {
        throw new CHttpException(404, 'The requested post does not exist.');
    }

    $existingLike = Like::model()->findByAttributes(array('user_id' => $userId, 'blog_post_id' => $id));
    if ($existingLike !== null) {
        // Unlike the post
        if ($existingLike->delete()) {
            echo CJSON::encode(['success' => true, 'liked' => false]);
        } else {
            echo CJSON::encode(['success' => false]);
        }
    } else {
        // Like the post
        $like = new Like();
        $like->user_id = $userId;
        $like->blog_post_id = $id;
        if ($like->save()) {
            echo CJSON::encode(['success' => true, 'liked' => true]);
        } else {
            echo CJSON::encode(['success' => false]);
        }
    }

    Yii::app()->end();
}

	
	public function actionUnlike($id) {
		$model = Like::model()->findByAttributes(array(
			'user_id' => Yii::app()->user->id,
			'blog_post_id' => $id,
		));
	
		if ($model !== null && $model->delete()) {
			$this->redirect(array('view', 'id' => $id));
		} else {
			Yii::app()->user->setFlash('error', 'Unable to unlike this post.');
			$this->redirect(array('view', 'id' => $id));
		}
	}
	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return BlogPost the loaded model
	 * @throws CHttpException
	 */
	// public function loadModel($id)
	// {
	// 	$model=BlogPost::model()->findByPk($id);
	// 	if($model===null)
	// 		throw new CHttpException(404,'The requested page does not exist.');
	// 	return $model;
	// }

	/**
	 * Performs the AJAX validation.
	 * @param BlogPost $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='blog-post-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
