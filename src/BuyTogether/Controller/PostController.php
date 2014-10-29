<?php
namespace BuyTogether\Controller;

use Fruit\Seed;
use Fruit\Session\PhpSession;
use BuyTogether\Model\User;
use BuyTogether\Model\Buy;
use BuyTogether\Model\Join;
use BuyTogether\Model\Thread;
use BuyTogether\Model\Post;
use BuyTogether\Model\ThreadPlus;

class PostController extends Seed
{
    public function addPost()
    {
        $session = new PhpSession;
        if ($_POST['content'] && $_POST['tid']) {
            $thread = Thread::load($_POST['tid']);
            $threadplus = $thread->getThreadPlus();
            $user = User::load($session->get('user'));
            $post = Post::create($thread, $user, $_POST['content']);
            if ($post instanceof Post) {
                $msg = array(
                    'status' => true,
                    'string' => '新增留言成功'
                );
                $msg['buy']['token'] = $threadplus->getBid();
            } else {
                $msg = array( 'status' => false, 'string' => '新增留言失敗，請稍候再試！！');
            }
            return self::getConfig()->getTmpl()->render('groupthread.html', $msg);
        }
    }

    public function editPost($pid)
    {
        $session = new PhpSession;
        $post = Post::load($pid);
        if ($post instanceof Post) {
            if ($session->get('user') == $post->getUid()) {
                $thread = $post->getThread();
                $threadplus = $thread->getThreadPlus();
                if ($_POST['content']) {
                    $post->setContent($_POST['content']);
                    $post->save();
                    $msg = array(
                        'status' => true,
                        'string' => '修改留言成功',
                        'token' => $threadplus->getBid()
                    );
                } else {
                    $msg = array(
                        'user' => $session->get('user'),
                        'token' => $pid,
                        'content' => $post->getContent(),
                    );
                    if ($post->getUpdateTime()) {
                        $msg['time'] = $post->getUpdateTime();
                    } else {
                        $msg['time'] = $post->getCreateTime();
                    }
                }
            }
        } else {
            $msg = array( 'status' => false, 'string' => '查無該留言');
        }
            return self::getConfig()->getTmpl()->render('editpost.html', $msg);
    }

    public function deletePost($pid)
    {
        $session = new PhpSession;
        $post = Post::load($pid);
        if ($post instanceof Post) {
            $thread = $post->getThread();
            $threadplus = $thread->getThreadPlus();
            if ($session->get('user') == $post->getUid()) {
                $post->delete();
                $msg = array(
                    'status' => true,
                    'string' => '刪除留言成功',
                    'buy.token' => $threadplus->getBid()
                );
            } else {
                $msg = array(
                    'status' => false,
                    'string' => '你沒有權限刪除該留言',
                    'token' => $threadplus->getBid()
                );
            }
            return self::getConfig()->getTmpl()->render('groupthread.html', $msg);
        }
    }

    public function showThread($bid)
    {
        $session = new PhpSession;
        $joins = Join::valid($bid, $session->get('user'));
        $buy = Buy::load($bid);
        $uid = User::getIdByEmail($buy->getOwner()); 
        if ($joins or ($uid == $session->get('user'))) {
            $threadplus = ThreadPlus::loadByBid($bid);
            $thread = $threadplus->getThread();
            $buy =$threadplus->getBuy();
            if ($thread instanceof Thread) {
                $posts = $thread->listPosts();
                $msg['user'] = $session->get('user');
                $msg['buy']['token'] = $bid;
                $msg['thread']['token'] = $thread->getToken();
                $msg['thread']['title'] = $thread->getTitle();
                $msg['thread']['owner'] = $buy->getOwner();
                if ($posts) {
                    foreach ($posts as $key) {
                        $post = Post::load($key);
                        $postuser = $post->getUser();
                        $msg['post'][$key]['token'] = $post->getToken();
                        $msg['post'][$key]['name'] = $postuser->getEmail();
                        $msg['post'][$key]['usertoken'] = $postuser->getToken();
                        $msg['post'][$key]['content'] = $post->getContent();
                        $msg['post'][$key]['createtime'] = $post->getCreateTime();
                        $msg['post'][$key]['updatetime'] = $post->getUpdateTime();
                    }
                }
            } else {
                $msg = array( 'status' => false, 'string' => '查無該討論串');
            }
        } else {
            $msg = array( 'status' => false, 'string' => '你不是該團購成員，無權查看');
        }
        return self::getConfig()->getTmpl()->render('groupthread.html', $msg);
    }
}
