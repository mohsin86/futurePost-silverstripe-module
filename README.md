# Future Post silverstripe module
A silverstripe module for controlling which content should show or not to show by selected date.

#####version
Try this for sliverstripe 3<version<4



#########
Add this two method in page.php file in your theme

```
    /*
     * For controlling parent post or Menu
     * @return all parent post
     */
    public function canView($member = null)
    {

        $result = SiteTree::get();
        $permissionForNotAdemin = Permission::check('ADMIN');
        $current_page_id = $this->ID;
        if(!$permissionForNotAdemin){
            $now = strtotime('now');
            if(isset($result)) {
                foreach($result as $page) {
                    $id = $page->record['ID'];
                    $pageData = Page::get()->byID( $id)->record;
                    if(isset($pageData['PublishDate'])){
                        $page_id = $pageData['ID'];
                        $published_data = $pageData['PublishDate'];

                        if($page_id == $current_page_id){
                            if($published_data && strtotime($published_data) <= $now) {
                                return true;
                            }else{
                                return false;
                            }
                        }
                    }
                }
            }
        }

       return true;


    }

    /*
     * For controlling all children post
     * @retun all Children
     */
    public function AllChildren(){
        $allChildren = parent::AllChildren();
        $now = strtotime('now');
        $children = [];

        $permissionForNotAdemin = Permission::check('ADMIN');
        if(!$permissionForNotAdemin){
            foreach ($allChildren as $record) {
                $pageData = $record->record;
                $childrenId = $pageData['ID'];
                $pageData = Page::get()->byID( $childrenId)->record;
                if(isset($pageData['PublishDate'])){

                    $published_data = $pageData['PublishDate'];
                    if($published_data && strtotime($published_data) <= $now) {
                        $children[] = $record;
                    }
                }

            }

            if(is_array($children)){
                return new ArrayList($children);
            }else{
                return $allChildren;
            }
       }else {
            return $allChildren;
        }
    }
    
```

### Feature

 - Admin Can select date for future publishing
 - Admin Can view all the content while logged in, future post functionality will disable for admin, since admin need to see all post while writing content
 - Post will not be shown for Normal user which date is set 
