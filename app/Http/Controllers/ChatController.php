<?php

namespace PMIS\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use PMIS\Chat;
use PMIS\Http\Resources\ChatFirebase;
use PMIS\Jobs\NotificationPushFirebase;
use PMIS\Notice;
use PMIS\NotificationListener;
use PMIS\Project;
use PMIS\User;

class ChatController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        date_default_timezone_set("Asia/Kathmandu");
        $this->userToken = $request->get('token');
        $this->user = User::whereToken($this->userToken)->first();
        if (!$this->user) {
            abort(403);
        }
    }

    public function chatMessageStore(Project $project, Request $request)
    {
        try {
            $data = $request->get('message');
            if (is_string($data)) {
                $data = \GuzzleHttp\json_decode(str_replace('\\', '/', $request->input('message')), true);
            }
            $chat_message = $project->chats()->create([
                'user_id' => $this->user->id,
                'type' => '',
                'message' => $data['text'],
                'order' => count($project->chats) + 1,
            ]);
        } catch (\Exception $exception) {
            \Log::error(['chat-store-exception' => $exception->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
        //push notification and store notifications on firebase
        NotificationPushFirebase::dispatch($chat_message);
        \Log::info(['chat-saved' => $chat_message->toArray()]);
        //fcm push notification to related users
        return response()->json('Message Sent', 200);
    }

    public function chatImageStore(Project $project, Request $request)
    {
        \Log::info($request->all());
        try {
            $image = $request->file('image_0');
            $imagename = time() . '-' . str_random() . '-' . $image->getClientOriginalName();
            $image->move('public/activityFiles', $imagename);
            $chat_message = $project->chats()->create([
                'user_id' => $this->user->id,
                'image' => $imagename,
                'order' => count($project->chats) + 1,
            ]);
        } catch (\Exception $exception) {
            \Log::error(['chat-image-exception' => $exception->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
        NotificationPushFirebase::dispatch($chat_message);
        \Log::info(['chat-image-saved' => $chat_message->toArray()]);
        //fcm push notification to related users
        return response()->json('Image Sent', 200);
    }

    public function chatFileStore(Project $project, Request $request)
    {

        \Log::info($request->all());
        try {
            $image = $request->file('file');
            $imagename = time() . '-' . str_random() . '-' . $image->getClientOriginalName();
            $image->move('public/activityFiles', $imagename);

            $chat_message = $project->chats()->create([
                'user_id' => $this->user->id,
                'order' => count($project->chats) + 1,
                'file' => $imagename,
            ]);
        } catch (\Exception $exception) {
            \Log::error(['chat-file-exception' => $exception->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
        NotificationPushFirebase::dispatch($chat_message);
        \Log::info(['chat-file-saved' => $chat_message->toArray()]);
        //fcm push notification to related users
        return response()->json('File Sent', 200);
    }

    public function lastSeen(Project $project)
    {
        \Log::info('test');
        $chat_screen = $this->user->chatScreens()->where('project_id', $project->id)->first();
        if (!$chat_screen) {
            $chat_screen = $this->user->chatScreens()->create([
                'project_id' => $project->id,
            ]);
        }
        $chat_screen->last_seen = date('Y-m-d H:i:s');
        $chat_screen->save();
        //update all the chat message related to this project of this user as seen
       /* $chat_notifications = NotificationListener::where('listener_id', $this->user->id)->where('type', 2)->where('seen',0)->where('created_at', '<',$chat_screen->last_seen);
        \Log::info(['test'=>count($chat_notifications->get())]);
        $count = 0;
        if(count($chat_notifications->get()))
            $count = count($chat_notifications->get());
        $serviceAccount = ServiceAccount::fromJsonFile(base_path('Firebase.json'));
        $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
        $database = $firebase->getDatabase();
        $database->getReference('notifications/user/' . $this->user->token . '/count')->set(--$count);

        $chat_notifications->update(['seen'=>true]);*/
        return response()->json('Success', 200);
    }

    public function notificationSeen($notification_id)
    {
        \Log::info(['notification_clicked_request' => request()->all() ]);
        $notification = NotificationListener::find($notification_id);
        $notification->seen = true;
        $notification->save();
        $serviceAccount = ServiceAccount::fromJsonFile(base_path('Firebase.json'));
        $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
        $database = $firebase->getDatabase();
        if($notification->type == 1) {//app notification
            $noti = $database->getReference('notifications/user/' . $this->user->token . '/app/' . $notification->id)->getValue();
            if(!$noti['seen']){
                $database->getReference('notifications/user/' . $this->user->token . '/app/' . $notification->id)->update(['seen' => $notification->seen]);
                //Update Count
                $count = $database->getReference('notifications/user/' . $this->user->token . '/count')->getValue();
                if($count)
                    $database->getReference('notifications/user/' . $this->user->token . '/count')->set(--$count);
            }
        }
        else{
            $noti = $database->getReference('notifications/user/' . $this->user->token . '/message/' . $notification->notice->project->id)->getValue();
            if(!$noti['seen']){
                $database->getReference('notifications/user/' . $this->user->token . '/message/' . $notification->notice->project->id)->update(['seen' => $notification->seen]);
                //Update Count
                $count = $database->getReference('notifications/user/' . $this->user->token . '/count')->getValue();
                if($count)
                    $database->getReference('notifications/user/' . $this->user->token . '/count')->set(--$count);
            }
        }
        return response()->json(['success'=>true],200);
    }
}
