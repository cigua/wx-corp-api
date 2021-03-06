<?php
namespace WXCorpApi\Manage;

use WXCorpApi\Exception;
use WXCorpApi\Manager;
use WXCorpApi\Traits;
use WXCorpApi\Uri;
use CurlX\Https;

/**
 * 企业号临时素材管理类
 */
class Media extends Manager
{
	use Traits;

    /**
     * 上传图片临时素材
     * @param string $realPath 图片完整路径
     * @return array
     */
    public function uploadImage($realPath)
	{
		return self::upload($realPath, 'image');
	}

    /**
     * 上传语音临时素材
     * @param string $realPath 语音完整路径
     * @return array
     */
    public function uploadVoice($realPath)
	{
		return self::upload($realPath, 'voice');
	}

    /**
     * 上传视频临时素材
     * @param string $realPath 视频完整路径
     * @return array
     */
    public function uploadVideo($realPath)
	{
		return self::upload($realPath, 'video');
	}

    /**
     * 上传文件临时素材
     * @param string $realPath 文件完整路径
     * @return array
     */
    public function uploadFile($realPath)
	{
		return self::upload($realPath, 'file');
	}

    /**
     * 获取临时素材文件
     * @param string $mediaId 临时素材媒体ID
     * @return \Curl\Curl
     * @throws Exception
     */
    public function get($mediaId)
	{
		$url = sprintf(Uri::MEDIA_GET, $this->token, $mediaId);

		$res = Https::get($url);

        if ($res->response_headers[2] == 'Content-Type: application/json; charset=utf-8') {
            $data = json_decode($res->response, true);
            if (isset($data['errcode'])) {
                throw new Exception($data['errmsg'], $data['errcode']);
            }
        }

        return $res;
	}

	private function upload($realPath, $type)
	{
		$url = sprintf(Uri::MEDIA_UPLOAD, $this->token, $type);

		$res = json_decode(Https::file($url, $realPath), true);

		self::throwException($res);

		return $res;
	}
}