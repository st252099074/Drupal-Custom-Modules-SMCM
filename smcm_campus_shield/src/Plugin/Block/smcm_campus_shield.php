<?php

namespace Drupal\smcm_campus_shield\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Campus Shield' Block.
 *
 * @Block(
 *   id = "smcm_campus_shield_block",
 *   admin_label = @Translation("SMCM Campus Shield block"),
 *   category = @Translation("SMCM Campus Shield"),
 * )
 */
class smcm_campus_shield extends BlockBase {

    /**
     * {@inheritdoc}
     */
    public function getCacheMaxAge() {
        return 0;
    }


  /**
   * {@inheritdoc}
   */
  public function build() {
      //'https://portal.publicsafetycloud.net/rss/feed/33'
      $source_emergency_file = file_get_contents('https://portal.publicsafetycloud.net/rss/feed/70', TRUE);
      $source_notification_file = file_get_contents('https://portal.publicsafetycloud.net/rss/feed/33', TRUE);

      $corrected_emergency_file= preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $source_emergency_file);
      $corrected_notification_file= preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $source_notification_file);

      $emergency_info = simplexml_load_string($corrected_emergency_file);
      $notification_info = simplexml_load_string($corrected_notification_file);

      $emergency_item  = $emergency_info ->channel->item;
      $notification_item  = $notification_info ->channel->item;
        if (!empty($emergency_item->description[0])){
               return [
               '#cache' => array('max-age' => 0,),
               '#theme' => 'smcm_campus_shield_template',
               '#emergency_description' => $emergency_item->description[0]
               ];
     }
      else
        {
            if(!empty($notification_item->description[0])){

             return [
              '#cache' => array('max-age' => 0,),
              '#theme' => 'smcm_campus_shield_template',
              '#notification_description' => $notification_item->description[0]
             ];
           }



          else {
              return [
              '#cache' => array('max-age' => 0,),
              '#theme' => 'smcm_campus_shield_template'
             ];

          }
      }
  }



}
