

<?php if ( bp_group_has_members() ) : ?>
 
 <div id="member-count" class="pag-count">
    <p>
      <?php bp_group_member_pagination_count() ?>
   </p> 
  </div>
 
  <div id="member-pagination" class="pagination-links">
    <?php bp_group_member_pagination() ?>
  </div>
 
  <ul id="member-list" class="item-list">
  <?php while ( bp_group_members() ) : bp_group_the_member(); ?>
 
    <li>
      <?php bp_group_member_avatar() ?>
    </li>
  <?php endwhile; ?>
  </ul>
 
<?php else: ?>
 
  <div id="message" class="info">
    <p>This scene has no members.</p>
  </div>
 
<?php endif;?>