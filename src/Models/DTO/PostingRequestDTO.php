<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Valitron\Validator;

class PostingRequestDTO extends BaseRequestDTO
{
    public string $title;
    public string $content;
    public int $userId;

    /**
     * @throws HttpException
     */
    public function __construct(array $requestData)
    {

        $validator = new Validator($requestData);
        $validator->rule('required', ['title', 'content', 'userId']);
        $validator->rule('lengthBetween', 'title', 10, 100)->message('title must be between 10 and 100 characters long');
        $validator->rule('numeric', 'userId');
        $isValid = $validator->validate();


        if (!$isValid) {
            throw new HttpException(json_encode($validator->errors()), Response::HTTP_BAD_REQUEST);
        }

        $this->title = $this->sanitize($requestData['title']);
        $this->content = $this->sanitize($requestData['content']);
        $this->userId = (int)$requestData['userId'];

    }
}
