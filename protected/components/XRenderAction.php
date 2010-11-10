<?php
/* 
 * XRenderAction class file.
 * 
 */

/**
 * XRenderAction enable direct access to controller methods.
 * So you can move code from action methods in a controller to a action class without changes.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 */
class XRenderAction extends CAction{

	/**
	 * Runs the action.
	 * This method is invoked by the controller owning this action.
	 */
	public function run(){
       // override this method
    }

	/**
	 * Redirects the browser to the specified URL or route (controller/action).
	 * @param mixed the URL to be redirected to. If the parameter is an array,
	 * the first element must be a route to a controller action and the rest
	 * are GET parameters in name-value pairs.
	 * @param boolean whether to terminate the current application after calling this method
	 * @param integer the HTTP status code. Defaults to 302. See {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html}
	 * for details about HTTP status code. This parameter has been available since version 1.0.4.
	 */
	public function redirect($url,$terminate=true,$statusCode=302)
	{
        $controller=$this->getController();
        $controller->redirect($url,$terminate,$statusCode);
	}

	/**
	 * Refreshes the current page.
	 * The effect of this method call is the same as user pressing the
	 * refresh button on the browser (without post data).
	 * @param boolean whether to terminate the current application after calling this method
	 * @param string the anchor that should be appended to the redirection URL.
	 * Defaults to empty. Make sure the anchor starts with '#' if you want to specify it.
	 * The parameter has been available since version 1.0.7.
	 **/
	public function refresh($terminate=true,$anchor='')
	{
        $controller=$this->getController();
        $controller->refresh($terminate,$anchor);
	}

	/**
	 * Renders a view with a layout.
	 *
	 * This method first calls {@link renderPartial} to render the view (called content view).
	 * It then renders the layout view which may embed the content view at appropriate place.
	 * In the layout view, the content view rendering result can be accessed via variable
	 * <code>$content</code>. At the end, it calls {@link processOutput} to insert scripts
	 * and dynamic contents if they are available.
	 *
	 * By default, the layout view script is "protected/views/layouts/main.php".
	 * This may be customized by changing {@link layout}.
	 *
	 * @param string name of the view to be rendered. See {@link getViewFile} for details
	 * about how the view script is resolved.
	 * @param array data to be extracted into PHP variables and made available to the view script
	 * @param boolean whether the rendering result should be returned instead of being displayed to end users.
	 * @return string the rendering result. Null if the rendering result is not required.
	 * @see renderPartial
	 * @see getLayoutFile
	 */
	public function render($view,$data=null,$return=false)
	{
        $controller=$this->getController();
		if($return)
			return $controller->render($view,$data,true);
		else
            $controller->render($view,$data,false);
	}

	/**
	 * Renders a static text string.
	 * The string will be inserted in the current controller layout and returned back.
	 * @param string the static text string
	 * @param boolean whether the rendering result should be returned instead of being displayed to end users.
	 * @return string the rendering result. Null if the rendering result is not required.
	 * @see getLayoutFile
	 */
	public function renderText($text,$return=false)
	{
        $controller=$this->getController();
		if($return)
			return $controller->renderText($text,true);
		else
			$controller->renderText($text,false);
	}

	/**
	 * Renders a view.
	 *
	 * The named view refers to a PHP script (resolved via {@link getViewFile})
	 * that is included by this method. If $data is an associative array,
	 * it will be extracted as PHP variables and made available to the script.
	 *
	 * This method differs from {@link render()} in that it does not
	 * apply a layout to the rendered result. It is thus mostly used
	 * in rendering a partial view, or an AJAX response.
	 *
	 * @param string name of the view to be rendered. See {@link getViewFile} for details
	 * about how the view script is resolved.
	 * @param array data to be extracted into PHP variables and made available to the view script
	 * @param boolean whether the rendering result should be returned instead of being displayed to end users
	 * @param boolean whether the rendering result should be postprocessed using {@link processOutput}.
	 * @return string the rendering result. Null if the rendering result is not required.
	 * @throws CException if the view does not exist
	 */
	public function renderPartial($view,$data=null,$return=false,$processOutput=false)
	{
        $controller=$this->getController();
        if($return)
            return $controller->renderPartial($view,$data,true,$processOutput);
        else
            $controller->renderPartial($view,$data,false,$processOutput);
	}

	/**
	 * Renders dynamic content returned by the specified callback.
	 * This method is used together with {@link COutputCache}. Dynamic contents
	 * will always show as their latest state even if the content surrounding them is being cached.
	 * This is especially useful when caching pages that are mostly static but contain some small
	 * dynamic regions, such as username or current time.
	 * We can use this method to render these dynamic regions to ensure they are always up-to-date.
	 *
	 * The first parameter to this method should be a valid PHP callback, while the rest parameters
	 * will be passed to the callback.
	 *
	 * Note, the callback and its parameter values will be serialized and saved in cache.
	 * Make sure they are serializable.
	 *
	 * @param callback a PHP callback which returns the needed dynamic content.
	 * When the callback is specified as a string, it will be first assumed to be a method of the current
	 * controller class. If the method does not exist, it is assumed to be a global PHP function.
	 * Note, the callback should return the dynamic content instead of echoing it.
	 */
	public function renderDynamic($callback)
	{
        $controller=$this->getController();
        $controller->renderDynamic($callback);
	}

	/**
	 * @param string the page title.
	 */
	public function setPageTitle($value)
	{
        $controller=$this->getController();
        $controller->setPageTitle($value);
	}

	/**
	 * Calls a other controller method.
	 * This is a PHP magic method that we override to implement the shortcut format methods.
	 * @param string the method name
	 * @param array method parameters
	 * @return mixed the method return value
	 */
	public function __call($name,$parameters)
	{
        $controller=$this->getController();
		if(method_exists($controller,$name))
			return call_user_func_array(array($controller,$name),$parameters);
		else
			return parent::__call($name,$parameters);
	}
}
?>
