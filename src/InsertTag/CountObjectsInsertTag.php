<?php

namespace Plenta\CasaCoreBundle\InsertTag;

use Contao\ContentModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Contao\CoreBundle\InsertTag\Resolver\InsertTagResolverNestedResolvedInterface;
use Plenta\CasaCoreBundle\Filter\ParameterGenerator;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsInsertTag('casa_objects_count')]
class CountObjectsInsertTag implements InsertTagResolverNestedResolvedInterface
{

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ParameterGenerator $parameterGenerator,
    ) {
    }

    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        $request = $this->requestStack->getCurrentRequest();

        $model = ContentModel::findByPk($request->attributes->get('contentModel'));

        $parameters = $this->parameterGenerator->generateFromRequest($request, $model);

        $columns = [];
        $values = [];

        foreach ($parameters as $parameter) {
            $columns = array_merge($columns, $parameter->getColumns());
            $values = array_merge($values, $parameter->getValues());
        }

        return new InsertTagResult(ObjectModel::countPublishedBy($columns, $values));
    }
}