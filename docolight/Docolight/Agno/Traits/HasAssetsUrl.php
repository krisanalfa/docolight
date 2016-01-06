<?php

namespace Docolight\Agno\Traits;

use Yii;

/**
 * A trait to manage your asset url. This one is tested only in Module. You can implement this trait to your module.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
trait HasAssetsUrl
{
    public $assetPath = 'asset';

    /**
     * A javascript API url for assets
     *
     * @var string
     */
    protected $assetsUrl;

    /**
     * Get absolute path of published assets
     *
     * @param string  $pathToFile Path to asset file you want to return.
     *                            If no argument was supplied, then it will return the root path of asset.
     * @param boolean $forceCopy  Whether we should copy the asset file or directory even if it is already
     *
     * @return string
     */
    public function asset($pathToFile = '', $forceCopy = false)
    {
        if (($this->assetsUrl === null) or $forceCopy) {
            $assetManager = Yii::app()
                ->getAssetManager();

            $oldForceCopy = $assetManager->forceCopy;

            $assetManager->forceCopy = $forceCopy;

            $this->assetsUrl = $assetManager
                ->publish(Yii::getPathOfAlias($this->getId().'.'.$this->assetPath), false, -1, $forceCopy);

            // Revert state
            $assetManager->forceCopy = $oldForceCopy;
        }

        return $pathToFile ? $this->assetsUrl.'/'.ltrim($pathToFile, '/') : $this->assetsUrl;
    }
}
