<?php
/**
 1. Establish a table schema on MySQL with columns id, first name, last name and favorite color (has to be one out of blue, green, red, yellow, black, white)
 2. Develop a search filter for those fields using Jquery & bootstrap :
 3. develop a small PHP search engine to search on those fields. 
 4. display result  in a grid with columns First name, Last name, and Favorite color usingbootstrap like in this example : http://getbootstrap.com/css/#grid-options
 5. when double clicking on the column containing the color preference, it should give the row background this color.
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $key_word   = $this->params()->fromPost('key_word',null);
        $map        = $this->getServiceLocator()->get('colorModel');
        $data       = $map->search($key_word);
        return new ViewModel(array('data'=>$data));
    }
}
