{*
TestLink Open Source Project - http://testlink.sourceforge.net/

show test plan tree for execution

@filesource	planTCNavigator.tpl
@internal revisions
@since 2.0
20110824 - franciscom - TICKET 4721: Left side tree manu - add specific navigator titles

*}

{lang_get var="labels" 
          s='btn_update_menu,btn_apply_filter,keyword,keywords_filter_help,title_navigator,
             btn_bulk_update_to_latest_version,
             filter_owner,TestPlan,test_plan,caption_nav_filters,
             build,filter_tcID,filter_on,filter_result,platform, include_unassigned_testcases'}

{include file="inc_head.tpl" openHead="yes"}
{include file="inc_ext_js.tpl" bResetEXTCss=1}

{* includes Ext.ux.CollapsiblePanel *}
<script type="text/javascript" src='gui/javascript/ext_extensions.js'></script>
<script type="text/javascript">
{* BUGID 4077 *}
treeCfg = { tree_div_id:'tree_div',root_name:"",root_id:0,root_href:"",
            loader:"", enableDD:false, dragDropBackEndUrl:'',children:"" };
Ext.onReady(function() {
	Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
	
			// Use a collapsible panel for filter settings
			// and place a help icon in ther header
			var settingsPanel = new Ext.ux.CollapsiblePanel({
				id: 'tl_exec_filter',
				applyTo: 'settings_panel',
				tools: [{
					id: 'help',
					handler: function(event, toolEl, panel) {
						show_help(help_localized_text);
					}
				}]
			});
			var filtersPanel = new Ext.ux.CollapsiblePanel({
				id: 'tl_exec_settings',
				applyTo: 'filter_panel'
			});
		});
	</script>

    <script type="text/javascript">
    {* BUGID 4077 *}
    treeCfg = { tree_div_id:'tree_div',root_name:"",root_id:0,root_href:"",
                loader:"", enableDD:false, dragDropBackEndUrl:'',children:"" };
    </script>
    
    <script type="text/javascript">
	    treeCfg.root_name = '{$gui->ajaxTree->root_node->name}';
	    treeCfg.root_id = {$gui->ajaxTree->root_node->id};
	    // BUGID 3049
	    // treeCfg.root_href = "javascript:PL({$gui->tplanID})";
	    // BUGID 3406
	    treeCfg.root_href = '{$gui->ajaxTree->root_node->href}';
	    treeCfg.children = {$gui->ajaxTree->children};
	    treeCfg.cookiePrefix = "{$gui->ajaxTree->cookiePrefix}";
    </script>
    
    <script type="text/javascript" src='gui/javascript/execTree.js'>
    </script>

<script type="text/javascript">
function pre_submit()
{
	document.getElementById('called_url').value = parent.workframe.location;
	return true;
}

/*
  function: update2latest
  args :
  returns:
*/
function update2latest(tproject_id,tplan_id)
{
	var action_url = fRoot+'/'+menuUrl+
				     "?doAction=doBulkUpdateToLatest&level=testplan&id="+tplan_id+
				     '&tproject_id=' + tproject_id + args;
	parent.workframe.location = action_url;
}
</script>


{* BUGID 3301 - js include file for simpler code, filter refactoring/redesign *}
{include file='inc_filter_panel_js.tpl'}

{* 
 * !!!!! IMPORTANT !!!!!
 * Above included file closes <head> tag and opens <body>, so this is not done here.
 *}

	
{assign var="cfg_section" value=$smarty.template|basename|replace:".tpl":""}
{config_load file="input_dimensions.conf" section=$cfg_section}

<h1 class="title">{$gui->title_navigator} {$gui->additional_string|escape}</h1>

{*
{assign var="keywordsFilterDisplayStyle" value=""}
{if $gui->keywordsFilterItemQty == 0}
    {assign var="keywordsFilterDisplayStyle" value="display:none;"}
{/if}
*}

{* BUGID 3301: include file for filter panel *}
{include file='inc_filter_panel.tpl'}

{* BUGID 4042 *}
{include file="inc_tree_control.tpl"}

{* BUGID 4077 *}
<div id="tree_div" style="overflow:auto; height:100%;border:1px solid #c3daf9;"></div>

<script type="text/javascript">
{if $gui->src_workframe != ''}
	parent.workframe.location='{$gui->src_workframe}';
{/if}
</script>

</body>
</html>