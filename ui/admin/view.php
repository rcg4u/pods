<?php
wp_enqueue_style( 'pods-form' );

/**
 * @var array   $fields
 * @var Pods_UI $obj
 */
?>

<div class="pods-submittable-fields">
<div id="poststuff" class="metabox-holder has-right-sidebar"> <!-- class "has-right-sidebar" preps for a sidebar... always present? -->
<div id="side-info-column" class="inner-sidebar">
	<div id="side-sortables" class="meta-box-sortables ui-sortable">
		<!-- BEGIN PUBLISH DIV -->
		<div id="submitdiv" class="postbox">
			<div class="handlediv" title="Click to toggle"><br /></div>
			<h3 class="hndle"><span><?php _e( 'Manage', 'pods' ); ?></span></h3>

			<div class="inside">
				<div class="submitbox" id="submitpost">
					<?php
					if ( isset( $pod->pod_data['fields']['created'] ) || isset( $pod->pod_data['fields']['modified'] ) || 0 < strlen( pods_v_sanitized( 'detail_url', $pod->pod_data ) ) ) {
						?>
						<div id="minor-publishing">
							<?php
							if ( 0 < strlen( pods_v_sanitized( 'detail_url', $pod->pod_data ) ) ) {
								?>
								<div id="minor-publishing-actions">
									<div id="preview-action">
										<a class="button" href="<?php echo $pod->field( 'detail_url' ); ?>" target="_blank"><?php echo sprintf( __( 'View %s', 'pods' ), $obj->item ); ?></a>
									</div>
									<div class="clear"></div>
								</div>
							<?php
							}

							if ( isset( $pod->pod_data['fields']['created'] ) || isset( $pod->pod_data['fields']['modified'] ) ) {
								?>
								<div id="misc-publishing-actions">
									<?php
									$datef = __( 'M j, Y @ G:i' );

									if ( isset( $pod->pod_data['fields']['created'] ) ) {
										$date = date_i18n( $datef, strtotime( $pod->field( 'created' ) ) );
										?>
										<div class="misc-pub-section curtime">
											<span id="timestamp"><?php _e( 'Created on', 'pods' ); ?>: <b><?php echo $date; ?></b></span>
										</div>
									<?php
									}

									if ( isset( $pod->pod_data['fields']['modified'] ) && $pod->display( 'created' ) != $pod->display( 'modified' ) ) {
										$date = date_i18n( $datef, strtotime( $pod->field( 'modified' ) ) );
										?>
										<div class="misc-pub-section curtime">
											<span id="timestamp"><?php _e( 'Last Modified', 'pods' ); ?>: <b><?php echo $date; ?></b></span>
										</div>
									<?php
									}
									?>
								</div>
							<?php
							}
							?>
						</div>
						<!-- /#minor-publishing -->
					<?php
					}
					?>

					<div id="major-publishing-actions">
						<?php
						if ( pods_is_admin( array( 'pods', 'pods_delete_' . $pod->pod ) ) && ! in_array( 'delete', $obj->actions_disabled ) && ! in_array( 'delete', $obj->actions_hidden ) ) {
							?>
							<div id="delete-action">
								<a class="submitdelete deletion" href="<?php echo pods_query_arg( array( 'action' => 'delete' ) ) ?>" onclick="return confirm('You are about to permanently delete this item\n Choose \'Cancel\' to stop, \'OK\' to delete.');"><?php _e( 'Delete', 'pods' ); ?></a>
							</div>
							<!-- /#delete-action -->
						<?php } ?>

						<div class="clear"></div>
					</div>
					<!-- /#major-publishing-actions -->
				</div>
				<!-- /#submitpost -->
			</div>
			<!-- /.inside -->
		</div>
		<!-- /#submitdiv --><!-- END PUBLISH DIV --><!-- TODO: minor column fields -->
		<?php
		if ( ! in_array( 'navigate', $obj->actions_disabled ) && ! in_array( 'navigate', $obj->actions_hidden ) ) {
			if ( ! isset( $singular_label ) ) {
				$singular_label = ucwords( str_replace( '_', ' ', $pod->pod_data['name'] ) );
			}

			$singular_label = pods_v( 'label', $pod->pod_data, $singular_label, true );
			$singular_label = pods_v( 'label_singular', $pod->pod_data, $singular_label, true );

			$prev = $pod->prev_id();
			$next = $pod->next_id();

			if ( 0 < $prev || 0 < $next ) {
				?>
				<div id="navigatediv" class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div>
					<h3 class="hndle"><span><?php _e( 'Navigate', 'pods' ); ?></span></h3>

					<div class="inside">
						<div class="pods-admin" id="navigatebox">
							<div id="navigation-actions">
								<?php
								if ( 0 < $prev ) {
									?>
									<a class="previous-item" href="<?php echo pods_query_arg( array( 'id' => $prev ), null, 'do' ); ?>"> <span>&laquo;</span>
										<?php echo sprintf( __( 'Previous %s', 'pods' ), $singular_label ); ?>
									</a>
								<?php
								}

								if ( 0 < $next ) {
									?>
									<a class="next-item" href="<?php echo pods_query_arg( array( 'id' => $next ), null, 'do' ); ?>">
										<?php echo sprintf( __( 'Next %s', 'pods' ), $singular_label ); ?>
										<span>&raquo;</span> </a>
								<?php
								}
								?>

								<div class="clear"></div>
							</div>
							<!-- /#navigation-actions -->
						</div>
						<!-- /#navigatebox -->
					</div>
					<!-- /.inside -->
				</div> <!-- /#navigatediv -->
			<?php
			}
		}
		?>
	</div>
	<!-- /#side-sortables -->
</div>
<!-- /#side-info-column -->

<div id="post-body">
	<div id="post-body-content">
		<?php
		$more = false;

		if ( $pod->pod_data['field_index'] != $pod->pod_data['field_id'] ) {
			foreach ( $fields as $k => $field ) {
				if ( $pod->pod_data['field_index'] != $field['name'] || 'text' != $field['type'] ) {
					continue;
				}

				$more  = true;
				$extra = '';

				$max_length = (int) pods_v_sanitized( 'maxlength', $field, pods_v_sanitized( $field['type'] . '_max_length', $field, 0 ), true );

				if ( 0 < $max_length ) {
					$extra .= ' maxlength="' . $max_length . '"';
				}
				?>
				<div id="titlediv">
					<div id="titlewrap">
						<h3><?php echo esc_html( $pod->index() ); ?></h3>
					</div>
					<!-- /#titlewrap -->
				</div>
				<!-- /#titlediv -->
				<?php
				unset( $fields[ $k ] );
			}
		}

		if ( 0 < count( $fields ) ) {
			?>
			<div id="normal-sortables" class="meta-box-sortables ui-sortable">
				<div id="pods-meta-box" class="postbox" style="">
					<div class="handlediv" title="Click to toggle"><br /></div>
					<h3 class="hndle">
						<span>
							<?php
							if ( $more ) {
								$title = __( 'More Fields', 'pods' );
							} else {
								$title = __( 'Fields', 'pods' );
							}

							/** This filter is documented in classes/Pods/Meta.php */
							echo apply_filters( 'pods_meta_default_box_title', $title, $pod, $fields );
							?>
						</span>
					</h3>

					<div class="inside">
						<table class="form-table pods-metabox">
							<?php
							foreach ( $fields as $field ) {
								if ( isset( $field['custom_display'] ) && is_callable( $field['custom_display'] ) ) {
									$value = call_user_func_array( $field['custom_display'], array( $pod->row(), $obj, $pod->field( $field['name'] ), $field['name'], $field ) );
								} else {
									$value = $pod->display( $field['name'] );
								}
								?>
								<tr class="form-field pods-field <?php echo 'pods-form-ui-row-type-' . $field['type'] . ' pods-form-ui-row-name-' . Pods_Form::clean( $field['name'], true ); ?>">
									<th scope="row" valign="top">
										<strong><?php echo $field['label']; ?></strong>
									</th>
									<td>
										<?php echo $value; ?>
									</td>
								</tr>
							<?php
							}
							?>
						</table>
					</div>
					<!-- /.inside -->
				</div>
				<!-- /#pods-meta-box -->
			</div>
			<!-- /#normal-sortables -->

		<?php } ?>

		<!--<div id="advanced-sortables" class="meta-box-sortables ui-sortable">
		</div>
		 /#advanced-sortables -->

	</div>
	<!-- /#post-body-content -->

	<br class="clear" />
</div>
<!-- /#post-body -->

<br class="clear" />
</div>
<!-- /#poststuff -->
</div>
<!-- /#pods-record -->
