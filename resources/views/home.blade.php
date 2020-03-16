@extends('layouts.master_layouts')
@section('content')
<div class="card">
					<div class="card-header header-elements-inline">
						<h5 class="card-title">Fallback language</h5>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                		<a class="list-icons-item" data-action="reload"></a>
		                		<a class="list-icons-item" data-action="remove"></a>
		                	</div>
	                	</div>
					</div>
                    <div class="card-body">
						<p class="mb-3">Example of defined <code>fallback</code> language. If user selects any language that is missed or detected user navigator language doesn't exist in your <code>/locales/</code> folder, defined fallback language will be loaded. In this example Spanish and Italian language files don't exist in <code>/locales/</code> folder. When one of these languages is selected, English language specified in <code>fallbackLng</code> option will be loaded.</p>

						<p class="font-weight-semibold">Change language directly:</p>
						<div class="navbar navbar-dark navbar-expand-md rounded mb-4">
							<div class="navbar-brand">
								<a href="index.html" class="d-inline-block">
									<img src="../../../../global_assets/images/logo_light.png" alt="">
								</a>
							</div>

							<div class="d-md-none">
								<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-i18-demo">
									<i class="icon-tree5"></i>
								</button>
							</div>

							<div class="collapse navbar-collapse" id="navbar-i18-demo">
								<ul class="navbar-nav">
									<li class="nav-item dropdown language-switch">
										<a class="navbar-nav-link dropdown-toggle" data-toggle="dropdown"></a>
										<div class="dropdown-menu">
											<a href="internationalization_fallback6dc5.html?lng=en" class="dropdown-item english">
												<img src="../../../../global_assets/images/lang/gb.png" class="img-flag" alt="">
												English
											</a>
											<a href="internationalization_fallbackb64c.html?lng=es" class="dropdown-item spanish">
												<img src="../../../../global_assets/images/lang/es.png" class="img-flag" alt="">
												Spanish
											</a>
											<a href="internationalization_fallbackcef0.html?lng=it" class="dropdown-item italian">
												<img src="../../../../global_assets/images/lang/it.png" class="img-flag" alt="">
												Italian
											</a>
										</div>
									</li>
								</ul>

								<ul class="navbar-nav ml-md-auto language-switch">
									<li class="nav-item">
										<a href="internationalization_fallback6dc5.html?lng=en" class="navbar-nav-link english">
											<img src="../../../../global_assets/images/lang/gb.png" class="img-flag mr-2" alt="">
											English
										</a>
									</li>
									<li class="nav-item">
										<a href="internationalization_fallbackb64c.html?lng=es" class="navbar-nav-link spanish">
											<img src="../../../../global_assets/images/lang/es.png" class="img-flag mr-2" alt="">
											Spanish
										</a>
									</li>
									<li class="nav-item">
										<a href="internationalization_fallbackcef0.html?lng=it" class="navbar-nav-link italian">
											<img src="../../../../global_assets/images/lang/it.png" class="img-flag mr-2" alt="">
											Italian
										</a>
									</li>
								</ul>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<p class="font-weight-semibold">Simple inline text:</p>
								<div class="sidebar sidebar-light sidebar-component position-static w-100 d-block mb-4">
									<div class="sidebar-content position-static">

										<!-- User menu -->
										<div class="card sidebar-user">
											<div class="card-body">
												<div class="media">
													<a href="#" class="mr-3">
														<img src="../../../../global_assets/images/demo/users/face11.jpg" width="38" height="38" class="rounded-circle" alt="">
													</a>

													<div class="media-body">
														<div class="media-title font-weight-semibold" data-i18n="nav_inline.user.name" data-fouc>Victoria Baker</div>
														<div class="font-size-xs opacity-50">
															<i class="icon-pin font-size-sm"></i> &nbsp;<span data-i18n="nav_inline.user.location" data-fouc>Santa Ana, CA</span>
														</div>
													</div>

													<div class="ml-3 align-self-center">
														<a href="#" class="text-default"><i class="icon-cog3"></i></a>
													</div>
												</div>
											</div>
										</div>
										<!-- /user menu -->


										<!-- Navigation -->
										<div class="card">
											<ul class="nav nav-sidebar" data-nav-type="accordion">
												<li class="nav-item-header">
													<div class="text-uppercase font-size-sm line-height-sm" data-i18n="nav_inline.nav.header" data-fouc>
														Sidebar header
													</div>
												</li>

												<li class="nav-item">
													<a href="#" class="nav-link">
														<i class="icon-strategy"></i>
														<span data-i18n="nav_inline.nav.top_level" data-fouc>Top level link</span>
													</a>
												</li>

												<li class="nav-item nav-item-submenu">
													<a href="#" class="nav-link">
														<i class="icon-stack2"></i>
														<span data-i18n="nav_inline.nav.with_children.main" data-fouc>With children</span>
													</a>

													<ul class="nav nav-group-sub">
														<li class="nav-item">
															<a href="#" class="nav-link" data-i18n="nav_inline.nav.with_children.second_one" data-fouc>Second level link 1</a>
														</li>
														<li class="nav-item">
															<a href="#" class="nav-link" data-i18n="nav_inline.nav.with_children.second_two" data-fouc>Second level link 2</a>
														</li>
													</ul>
												</li>

												<li class="nav-item nav-item-submenu">
													<a href="#" class="nav-link">
														<i class="icon-cube3"></i>
														<span data-i18n="nav_inline.nav.multiple_levels.main" data-fouc>Multiple levels</span>
													</a>

													<ul class="nav nav-group-sub">
														<li class="nav-item">
															<a href="#" class="nav-link" data-i18n="nav_inline.nav.multiple_levels.second_one" data-fouc>Second level link 1</a>
														</li>

														<li class="nav-item nav-item-submenu">
															<a href="#" class="nav-link" data-i18n="nav_inline.nav.multiple_levels.second_child.main" data-fouc>Second level with child</a>

															<ul class="nav nav-group-sub">
																<li class="nav-item">
																	<a href="#" class="nav-link" data-i18n="nav_inline.nav.multiple_levels.second_child.third_one" data-fouc>Third level link 1</a>
																</li>
																<li class="nav-item">
																	<a href="#" class="nav-link" data-i18n="nav_inline.nav.multiple_levels.second_child.third_two" data-fouc>Third level link 2</a>
																</li>
															</ul>
														</li>
														<li class="nav-item">
															<a href="#" class="nav-link" data-i18n="nav_inline.nav.multiple_levels.second_three" data-fouc>Second level link 3</a>
														</li>
													</ul>
												</li>

												<li class="nav-item">
													<a href="#" class="nav-link disabled">
														<i class="icon-make-group"></i>
														<span data-i18n="nav_inline.nav.multiple_levels.disabled" data-fouc>Disabled link</span>
													</a>
												</li>
											</ul>
										</div>
										<!-- /navigation -->

									</div>
								</div>
							</div>

							<div class="col-md-6">
								<p class="font-weight-semibold">Including attributes, tooltips, badges etc:</p>
								<div class="sidebar sidebar-light sidebar-component position-static w-100 d-block mb-4">
									<div class="sidebar-content position-static">

										<!-- User menu -->
										<div class="card sidebar-user">
											<div class="card-body">
												<div class="media">
													<a href="#" class="mr-3">
														<img src="../../../../global_assets/images/demo/users/face11.jpg" width="38" height="38" class="rounded-circle" alt="">
													</a>

													<div class="media-body">
														<div class="media-title font-weight-semibold" data-i18n="nav_inline.user.name" data-fouc>Victoria Baker</div>
														<div class="font-size-xs opacity-50">
															<i class="icon-pin font-size-sm"></i> &nbsp;<span data-i18n="nav_inline.user.location" data-fouc>Santa Ana, CA</span>
														</div>
													</div>

													<div class="ml-3 align-self-center">
														<a href="#" class="text-default">
															<i class="icon-cog3"></i>
														</a>
													</div>
												</div>
											</div>
										</div>
										<!-- /user menu -->


										<!-- Navigation -->
										<div class="card">
											<ul class="nav nav-sidebar" data-nav-type="accordion">
												<li class="nav-item-header d-flex">
													<div class="text-uppercase font-size-sm line-height-sm" data-i18n="nav_advanced.nav.header" data-fouc>
														
													</div>
													<a href="#" class="align-self-start ml-auto" data-popup="tooltip" title="Tooltip" data-i18n="[title]nav_advanced.nav.tooltip_text;[data-original-title]nav_advanced.nav.tooltip_text;nav_advanced.nav.tooltip" data-placement="left" data-container="body" data-fouc>Tooltip text</a>
												</li>

												<li class="nav-item">
													<a href="#" class="nav-link">
														<i class="icon-strategy"></i>
														<span data-i18n="nav_advanced.nav.inline_element" data-fouc>Inline element</span>
														<span class="badge bg-pink-400 ml-auto" data-i18n="nav_advanced.nav.badges.new" data-fouc>New</span>
													</a>
												</li>

												<li class="nav-item">
													<a href="#" class="nav-link">
														<i class="icon-stack2"></i>
														<span data-i18n="nav_advanced.nav.insert" data-fouc>Insert HTML from JSON</span>
														<span class="ml-auto" data-i18n="[html]nav_advanced.nav.badges.done" data-fouc></span>
													</a>
												</li>

												<li class="nav-item">
													<a href="#" class="nav-link">
														<i class="icon-cube3"></i>
														<span data-i18n="nav_advanced.nav.inline_text" data-fouc>Plain text</span>
														<span class="text-muted font-weight-normal ml-auto" data-i18n="nav_advanced.nav.badges.text" data-fouc>Inline text</span>
													</a>
												</li>

												<li class="nav-item">
													<a href="#" class="nav-link disabled">
														<i class="icon-make-group"></i>
														<span data-i18n="nav_advanced.nav.multiple_levels.disabled" data-fouc>Disabled link</span>
														<span class="badge bg-primary ml-auto" data-i18n="nav_advanced.nav.badges.fixed" data-fouc>Fixed</span>
													</a>
												</li>
											</ul>
										</div>
										<!-- /navigation -->

									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<p class="font-weight-semibold">Example markup:</p>
								<pre class="mb-3" data-line="3,8,13"></pre>
							</div>

							<div class="col-md-6">
								<p class="font-weight-semibold">JS code example:</p>
								<pre class="mb-3" data-line="9"></pre>
							</div>
						</div>
					</div>
				</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<a href="internationalization_fallback.html" class="breadcrumb-item">Translation</a>
							<span class="breadcrumb-item active">Fallback language</span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
					<div class="header-elements d-none">
						<div class="breadcrumb justify-content-center">
							<a href="#" class="breadcrumb-elements-item">
								<i class="icon-comment-discussion mr-2"></i>
								Support
							</a>

							<div class="breadcrumb-elements-item dropdown p-0">
								<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
									<i class="icon-gear mr-2"></i>
									Settings
								</a>

								<div class="dropdown-menu dropdown-menu-right">
									<a href="#" class="dropdown-item"><i class="icon-user-lock"></i> Account security</a>
									<a href="#" class="dropdown-item"><i class="icon-statistics"></i> Analytics</a>
									<a href="#" class="dropdown-item"><i class="icon-accessibility"></i> Accessibility</a>
									<div class="dropdown-divider"></div>
									<a href="#" class="dropdown-item"><i class="icon-gear"></i> All settings</a>
								</div>
							</div>
						</div>
					</div>
				</div>
@stop