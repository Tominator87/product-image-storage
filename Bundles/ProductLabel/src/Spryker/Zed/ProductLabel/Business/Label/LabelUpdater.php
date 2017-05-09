<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class LabelUpdater implements LabelUpdaterInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     */
    public function __construct(ProductLabelQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function update(ProductLabelTransfer $productLabelTransfer)
    {
        $this->assertProductLabel($productLabelTransfer);

        $this->handleDatabaseTransaction(function () use ($productLabelTransfer) {
            $this->executeUpdateTransaction($productLabelTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function assertProductLabel(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelTransfer
            ->requireIdProductLabel()
            ->requireName()
            ->requireIsActive()
            ->requireIsExclusive()
            ->requirePosition();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function executeUpdateTransaction(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelTransfer = $this->persistLabel($productLabelTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function persistLabel(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelEntity = $this->getEntityById($productLabelTransfer->getIdProductLabel());
        $productLabelEntity = $this->updateEntityFromTransfer($productLabelEntity, $productLabelTransfer);
        $productLabelEntity->save();

        return $this->updateTransferFromEntity($productLabelTransfer, $productLabelEntity);
    }

    /**
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel
     */
    protected function getEntityById($idProductLabel)
    {
        $productLabelEntity = $this
            ->queryContainer
            ->queryProductLabelById($idProductLabel)
            ->findOne();

        if (!$productLabelEntity) {

        }

        return $productLabelEntity;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel
     */
    protected function updateEntityFromTransfer(
        SpyProductLabel $productLabelEntity,
        ProductLabelTransfer $productLabelTransfer
    ) {
        $productLabelEntity->fromArray($productLabelTransfer->toArray());

        return $productLabelEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function updateTransferFromEntity(
        ProductLabelTransfer $productLabelTransfer,
        SpyProductLabel $productLabelEntity
    ) {
        $productLabelTransfer->fromArray($productLabelEntity->toArray(), true);

        return $productLabelTransfer;
    }

}
