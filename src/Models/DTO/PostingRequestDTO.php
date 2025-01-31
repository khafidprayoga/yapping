<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Valitron\Validator;

class PostingRequestDTO
{
    public string $title;
    public string $content;
    public string $userId;

    /**
     * @throws HttpException
     */
    public function __construct(array $requestData)
    {

        $validator = new Validator($requestData);
        $validator->rule('required', ['title', 'content', 'userId']);

        // todo
        $isValid = $validator->validate();

        if (!$isValid) {

        }

        $this->title = $requestData['title'];
        $this->content = $requestData['content'];
        $this->userId = $requestData['userId'];
    }
}
