<?php
/**
 * @package Mysypply_App
 * @author  Stenik Symfony Team <symfony@stenik.bg>
 */

namespace App\Utils;

use App\Entity\CustomerRequestInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class CustomerRequest
 * @package App\Utils
 * @author  Lubo Grozdanov <grozdanov.lubo@gmail.com>
 */
class CustomerRequest
{
    /**
     * @param \App\Entity\CustomerRequestInterface $customerRequest
     *
     * @return \App\Entity\CustomerRequestInterface
     */
    public function orderSupplierProposal(CustomerRequestInterface $customerRequest)
    {
        $supplierProposals   = $customerRequest->getSupplierProposals();
        $evaluationCriterias = $customerRequest->getEvaluationCriterias();

        $forComprasion = [];
        $namePercent   = [];
        foreach ($evaluationCriterias as $evaluationCriteria) {
            $forComprasion[$evaluationCriteria->getName()] = [];
            /*
             * if there is more than one criterion with the same weight, we subtract 1 from second
             * because we know that this criteria is added at second place
             */
            if (!isset($forComprasion[$evaluationCriteria->getWeight()])) {
                $namePercent[$evaluationCriteria->getName()] = $evaluationCriteria->getWeight();
            } else {
                $namePercent[$evaluationCriteria->getName()] = $evaluationCriteria->getWeight() - 1;
            }
        }

        $beforeOrderedSp = [];
        foreach ($supplierProposals as $supplierProposal) {
            $beforeOrderedSp[$supplierProposal->getId()] = $supplierProposal;
            foreach ($supplierProposal->getEvaluationScores() as $evaluationScore) {
                $ec                                                        = $evaluationScore->getEvaluationCriteria();
                $forComprasion[$ec->getName()][$supplierProposal->getId()] = $evaluationScore->getDescription();
            }
        }

        arsort($namePercent);
        $orderedProposal = [];
        foreach ($namePercent as $name => $percent) {
            natcasesort($forComprasion[$name]);
            uasort($forComprasion[$name], [$this, 'cmp']);
            $arrayCountValues = array_count_values($forComprasion[$name]);
            $n               = $supplierProposals->count() - 1;
            foreach ($forComprasion[$name] as $supplierProposalId => $evScoreDesc) {
                if ($arrayCountValues[$evScoreDesc] == 1 && !in_array($supplierProposalId, $orderedProposal)) {
                    $orderedProposal[$n] = $supplierProposalId;
                }
                $n--;
            }
        }

        ksort($orderedProposal);
        $orderedCollection = new ArrayCollection();
        foreach ($orderedProposal as $supplierProposalId) {
            $orderedCollection->add($beforeOrderedSp[$supplierProposalId]);
        }
        $customerRequest->setSupplierProposals($orderedCollection);

        return $customerRequest;
    }

    /**
     * @param $a
     * @param $b
     *
     * @return int
     */
    private function cmp($a, $b)
    {
        preg_match_all('/(\s\d+(\.|\,)+\d\s?)/', $a, $aMatches);
        preg_match_all('/(\s\d+(\.|\,)+\d\s?)/', $b, $bMatches);

        if (count($aMatches[0]) !== count($bMatches[0])) {
            return 0;
        }

        foreach ($aMatches[0] as $k => $v) {
            $v  = trim($v);
            $bv = trim($bMatches[0][$k]);
            if ($v > $bv) {
                return 1;
            } elseif ($v < $bv) {
                return -1;
            }
        }

        return 0;
    }

}
