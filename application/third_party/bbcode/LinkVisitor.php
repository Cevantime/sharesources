<?php

/**
 * Description of LinkAcceptor
 *
 * @author cevantime
 */
class LinkVisitor implements JBBCode\NodeVisitor
{

    private $linkTags = ['h2', 'h3', 'h4', 'h5', 'h6', 'p'];
    private $numbers = [];

    public function visitDocumentElement(\JBBCode\DocumentElement $documentElement)
    {
        $this->numbers = array_fill(0, count($this->linkTags), 0);
        foreach ($documentElement->getChildren() as $child) {
            if ($child instanceof JBBCode\ElementNode) {
                $this->visitElementNode($child);
            }
        }
    }

    public function visitElementNode(\JBBCode\ElementNode $elementNode)
    {
        $i = 0;
        foreach ($this->linkTags as $tagName) {
            if ($elementNode->getTagName() === $tagName) {
                $this->incArray($this->numbers, $i);
                $elementNode->setAttribute([$this->buildSectionName($this->numbers)]);
            }
            $i++;
        }
        foreach ($elementNode->getChildren() as $child) {
            if ($child instanceof JBBCode\ElementNode) {
                $this->visitElementNode($child);
            }
        }
    }

    public function visitTextNode(\JBBCode\TextNode $textNode)
    {
        // nothing to do here
    }
    
    public function getLinkTags()
    {
        return $this->linkTags;
    }
    
    private function incArray(&$numbers, $index)
    {
        $numbers[$index]++;
        for($i = $index + 1; $i<count($numbers); $i++){
            $numbers[$i] = 0;
        }
    }
    
    private function buildSectionName($numbers)
    {
        $linkRadical = 'link';
        foreach ($numbers as $number){
            $linkRadical .= '-'.$number;
        }
        return $linkRadical;
    }

}
