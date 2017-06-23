<?php
declare(strict_types=1);

namespace Eps\Req2CmdBundle\ParameterMapper;

use Symfony\Component\HttpFoundation\Request;

interface ParamMapperInterface
{
    public function map(Request $request, array $propsMap): array;
}
