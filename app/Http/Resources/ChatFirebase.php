<?php

namespace PMIS\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatFirebase extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $text_field = 'text';
        if($this->file){
            $text_field = 'file_name';
            $mime = mime_content_type('public/activityFiles/'.$this->file);
            if(strstr($mime, "video/")){
                $text_field = 'video';
            }else if(strstr($mime, "image/")){
                // this code for image
            }
        }
        return [
            '_id' => $this->id,
            'createdAt' => date("Y-m-d H:i:s.u", strtotime($this->created_at)),
            'order' => -strtotime($this->created_at),
            'status' => $this->status?:0,
            $text_field => $this->file ? config('app.app_url').'/public/activityFiles/'.$this->file: $this->message,
            'file' => $this->file ? config('app.app_url').'/public/activityFiles/'.$this->file: null,
            'image' => $this->image ? config('app.app_url').'/public/activityFiles/'.$this->image:null,
            'type' => $this->type,
            'uidFrom' => $this->user->token,
            'uidTo' => $this->project_id,
            'user' => ['_id'=>$this->user->token,'name'=>$this->user->name],
        ];
    }
}
