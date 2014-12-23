<?php
class Form extends CForm
{
	protected function init()
	{
		
	}
	
	public function renderBody()
	{
		$output='';
		if($this->title!==null)
		{
//			if($this->getParent() instanceof self)
//			{
//				$attributes=$this->attributes;
//				unset($attributes['name'],$attributes['type']);
//				$output=CHtml::openTag('div', $attributes).$this->title."</div>\n";
//			}
//			else
				$output="<div class=\"form_title\">".$this->title."</div>\n";
		}
		
		

		if($this->description!==null)
			$output.="<div class=\"form_Alarm\">\n".$this->description."</div>\n";

		if($this->showErrorSummary && ($model=$this->getModel(false))!==null)
			$output.=$this->getActiveFormWidget()->errorSummary($model)."\n";
			
		$output.= "<table class=\"tb tb2\">\n";	
		
		$output.=$this->renderElements()."\n".$this->renderButtons()."\n";

//		if($this->title!==null)
//			$output.="</fieldset>\n";
	
		$output .= "</table>\n";

		return $output;
	}
	
	/**
	 * Renders the {@link buttons} in this form.
	 * @return string the rendering result
	 */
	public function renderButtons()
	{
		$output='';
		foreach($this->getButtons() as $button)
			$output.=$this->renderElement($button);
		return $output!=='' ? "<tr class=\"row buttons\"><td>".$output."</td></tr>\n" : '';
	}

	/**
	 * Renders a single element which could be an input element, a sub-form, a string, or a button.
	 * @param mixed $element the form element to be rendered. This can be either a {@link CFormElement} instance
	 * or a string representing the name of the form element.
	 * @return string the rendering result
	 */
	public function renderElement($element)
	{
		if(is_string($element))
		{
			if(($e=$this[$element])===null && ($e=$this->getButtons()->itemAt($element))===null)
				return $element;
			else
				$element=$e;
		}
		if($element->getVisible())
		{
			if($element instanceof CFormInputElement)
			{
				if($element->layout == "{label}\n{input}\n{hint}\n{error}")
					$element->layout = "<td class=\"td27\" >{label}</td></tr><tr><td>{input}\n{hint}\n{error}</td>";
				
				if($element->type==='hidden')
					return "<tr style=\"visibility:hidden\">\n".$element->render()."</tr>\n";
				else
					return "<tr class=\"row field_{$element->name}\">\n".$element->render()."</tr>\n";
			}
			else if($element instanceof CFormButtonElement)
				return $element->render()."\n";
			else
				return $element->render();
		}
		return '';
	}
}