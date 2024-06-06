<?php

namespace Modules\Purchase\Http\Services;

use App\Traits\InvoiceTrait;

class PurchaseService
{
    use InvoiceTrait;

    public function calcQte($detail, $isComplete, $quantityInDBTable)
    {
        if ($isComplete) {
            return $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
        }

        return $quantityInDBTable;
    }

    public function calcUpdatedQte($oldPurchaseEffected, $detail, $oldDetail, $isComplete, $old_isComplete, $quantityInDBTable)
    {
        $qte = $quantityInDBTable;
        if ($oldPurchaseEffected) {
            if ($isComplete) {
                if ($oldDetail['quantity'] == $detail['quantity']) {
                    $qte = $quantityInDBTable;
                } else {
                    $sum = $oldDetail['quantity'] - $detail['quantity'];
                    $qte = $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $sum);
                }
            } else {
                $qte = $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $oldDetail['quantity']);
            }
        } else {
            if ($old_isComplete) {
                $qte = $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
            } else {
                if (!$old_isComplete && $isComplete) {
                    $qte = $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
                }

                if ($old_isComplete && !$isComplete) {
                    if ($oldDetail['quantity'] == $detail['quantity']) {
                        $qte = $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
                    } else {
                        $sum = $oldDetail['quantity'] - $detail['quantity'];
                        $qte = $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $sum);
                    }
                }

                if ($old_isComplete && $isComplete) {
                    if ($oldDetail['quantity'] == $detail['quantity']) {
                        $qte = $quantityInDBTable;
                    } else {
                        $sum = $oldDetail['quantity'] - $detail['quantity'];
                        $qte = $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $sum);
                    }
                }

                if ((!$old_isComplete && !$isComplete)) {
                    $qte = $quantityInDBTable;
                }
            }
        }
        return $qte;
    }

    public function destroyDetails($invoice, $deletedDetails, $old_isComplete)
    {
        $deletedIds = [];

        if (count($deletedDetails)) {
            foreach ($deletedDetails as $deletedDetail) {
                if (isset($deletedDetail['id'])) {
                    $deletedIds[] = $deletedDetail['id'];

                    if ($old_isComplete) {
                        $quantity = self::qteStockInDB(
                            $invoice,
                            $deletedDetail
                        ) - $this->stockyByUnit($deletedDetail['unit_id'], $deletedDetail['quantity']);

                        $stock = $this->updateStockInDB($invoice, $deletedDetail, $quantity);

                        $quantityInPatch = self::qtePatchInDB(
                            $invoice,
                            $deletedDetail
                        ) - $this->stockyByUnit($deletedDetail['unit_id'], $deletedDetail['quantity']);

                        $this->updateOrCreatePatchInDB($invoice, $deletedDetail, $quantityInPatch, $stock);
                    }
                }
            }
            $invoice->details()->whereIn('id', $deletedIds)->delete();
        }
    }
}
