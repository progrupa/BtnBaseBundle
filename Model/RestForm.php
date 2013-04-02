<?php

namespace Btn\BaseBundle\Model;

// rest form helper class
class RestForm
{
    /**
     * Translator service
     *
     * @var Translator $translator
     **/
    private $translator = null;

    /**
     * Construct
     *
     * @param  Translator $trasnlator
     * @return void
     **/
    public function __construct($translator)
    {
        $this->translator = $translator;
    }

    /**
     * Convert symfony form object into the array
     *
     * @return array $jsonForm
     **/
    public function createJsonForm($form)
    {
        $jsonForm = array();
        foreach ($form->createView()->getChildren() as $key => $child) {

            $types  = $child->vars['block_prefixes'];
            $type   = ($types[2] == 'text' && isset($types[3]) && (strpos($types[3], '_') === FALSE)) ? $types[3] : $types[2];
            $format = '';

            if ($type == 'datetime' || $type == 'date') {
                $type   = 'text';
                $format = 'datetime';
            }

            $jsonForm[] = array(
                'name'   => $key,
                'type'   => $type,
                'format' => $format
            );

            if ($type == 'choice') {
                $choicesArr = array();
                $choices    = $child->vars['choices'];

                foreach ($choices as $choice) {
                    $choicesArr[] = array('label' => $choice->label, 'value' => $choice->value);
                }

                $jsonForm[count($jsonForm) - 1]['choices'] = $choicesArr;
            }
        }

        return $jsonForm;
    }


    /**
     * Rest request validation
     *
     * @param Request $request
     * @param Entity  $entity
     * @param Form    $form
     * @param integer $id
     * @return array  $validationArr
     **/
    public function validateRequest($request, $entity, $form, $id = NULL)
    {
        $request->request->set($form->getName(), (array)json_decode($request->getContent()));
        $form->bind($request);

        $validationArr = array(
            'isValid' => $form->isValid(),
            'entity'  => $entity
        );

        $validationArr['errors'] = (!$validationArr['isValid']) ? $this->getFormErrors($form) : NULL;

        return $validationArr;
    }

    /**
     * Return form errors as array
     *
     * @param  form  $form
     * @return array $errors
     **/
    public function getFormErrors($form)
    {
        foreach ($form->getErrors() as $e) {
            $errors[] = $this->translator->trans($this->convertFormErrorObjToString($e), array(), 'validators');
        }

        foreach ($this->getAllErrors($form->getChildren()) as $key => $error) {
            $errors[$key] = $this->translator->trans($error[0], array(), 'validators');
        }

        return $errors;
    }

    /**
     * todo
     *
     * @return void
     **/
    private function getAllErrors($children, $template = TRUE)
    {
        $this->getAllFormErrors($children);

        return $this->allErrors;
    }

    private function getAllFormErrors($children, $template = TRUE)
    {
        foreach ($children as $child) {
            if ($child->hasErrors()) {
                $vars   = $child->createView()->getVars();
                $errors = $child->getErrors();
                foreach ($errors as $error) {
                    $this->allErrors[$vars["name"]][] = $this->convertFormErrorObjToString($error);
                }
            }

            if ($child->hasChildren()) {
                $this->getAllErrors($child);
            }
        }
    }

    private function convertFormErrorObjToString($error)
    {
        $errorMessageTemplate = $error->getMessageTemplate();
        foreach ($error->getMessageParameters() as $key => $value) {
            $errorMessageTemplate = str_replace($key, $value, $errorMessageTemplate);
        }
        return $errorMessageTemplate;
    }
}