<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'description', 'views', 'path', 'videoImagePath', 'user_id', 'genre', 'course_id', 'duration'];

    // each separate chunk will have 500 KB, unless we're at the end of the video
    public static $usualChunkLen = 500000;

    public static function createChunk(Video $video, $startingPositionUnfiltered)
    {
        // convert 'bytes=X-' to 'X'
        //ob_get_clean();
        $startingPosition = self::extractNumeric($startingPositionUnfiltered);

        $pathToVideo = 'storage/'.$video->path;
        // length of the entire video from which we want to extract chunk
        $videoLen = filesize($pathToVideo);

        // if we're at the end of the video, the last chunk will be shorter, otherwise sema as $usualChunkLen
        $currentChunkLen = min((self::$usualChunkLen), $videoLen - $startingPosition);

        // $video refers to metadata of video, $actualVideo refers to the real video, which we will read in binary
        $actualVideo = fopen($pathToVideo, 'rb');

        // jump to the requested starting position in the video and read
        fseek($actualVideo, $startingPosition);
        $chunk = fread($actualVideo, $currentChunkLen);

        // set response headers that will tell browser which chunk is being sent
        self::setHeaders($startingPosition, $currentChunkLen, $videoLen);
        fclose($actualVideo);
        return $chunk;
    }

    public static function setHeaders($startingPosition, $currentChunkLen, $videoLen)
        // sets HTTP response headers
    {
        header('HTTP/1.1 206 Partial Content');
        header('Status: 206');
        header('Content-Type: video/mp4');
        header('Connection: keep-alive');
        //header('Cache-Control: public, max-age=604800');
        //header('ETag: b3a64df551425fcc55e4d42a148795d9f25f89d5');
        header("Content-Transfer-Encoding: binary");
        //header('Accept-Ranges: 0-'.$videoLen.'');
        header('Accept-Ranges: bytes');
        header('Content-Length: '.$currentChunkLen.'');
        header('Content-Range: bytes '.$startingPosition.'-'.($startingPosition+$currentChunkLen-1).'/'.$videoLen);
    }

    public static function extractNumeric($str){
        // from given string return only numeric chars
        $numeric = "";
        for($i=0; $i< strlen($str); $i++){
            $currentChar = substr($str, $i, 1);
            if(is_numeric($currentChar)) $numeric.= $currentChar;
        }
        return $numeric;
    }

    public function user()
        // a certain video is owned by 1 user
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function findMatching($limitations)
        // find all videos that match user's search request (title, author)
    {
        $videos = Video::where('title', 'like', '%'.$limitations.'%')
                        ->orWhere('user_id', User::where('name', 'like', '%'.$limitations.'%')->first()->id ?? -1);
        return $videos;
    }



    public static function filterByGenre($genre)
    {
        return Video::where('genre', 'like', '%'.$genre.'%');
    }
}
