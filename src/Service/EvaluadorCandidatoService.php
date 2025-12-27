<?php

namespace App\Service;


use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\Component\DependencyInjection\Attribute\Target;

class EvaluadorCandidatoService
{
    private AgentInterface $agent;
    public function __construct(#[Target('evaluador_candidatos_ia')] AgentInterface $agent) {
        $this->agent = $agent;
    }

    public function evaluate(array $datos_usuario, array $datos_oferta_empleo)
    {

        // 1. Convertimos los arrays a JSON para que la IA los pueda leer
        $usuario_json = json_encode($datos_usuario, JSON_UNESCAPED_UNICODE);
        $oferta_json = json_encode($datos_oferta_empleo, JSON_UNESCAPED_UNICODE);

        // 2. Construimos el mensaje usando comillas dobles
        $userMessage = "Analiza este candidato para la oferta de trabajo:\n\n" .
            "DATOS CANDIDATO:\n$usuario_json\n\n" .
            "DATOS OFERTA EMPLEO:\n$oferta_json";

        $messages = new MessageBag(
            Message::ofUser($userMessage),
        );

        $result = $this->agent->call($messages);
        return $result->getContent();
    }

}