В mPDF обнаружена "ошибка"! 
строка 18587
$this->restoreInlineProperties(array_pop($this->InlineProperties[$tag]));
заменить на 
$InlineProperties = array_pop($this->InlineProperties[$tag]);
$this->restoreInlineProperties($InlineProperties);