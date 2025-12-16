<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared("

            CREATE PROCEDURE finalizar_votacao(
                IN p_eleitor_id INT,
                IN p_etapa_id INT,
                IN p_nome_votacao VARCHAR(255),
                IN p_nome_eleitor VARCHAR(255),
                IN p_cpf VARCHAR(20),
                IN p_ip VARCHAR(50)
            )
            BEGIN
                DECLARE v_comprovante_id INT;
                DECLARE v_chave VARCHAR(100);
                DECLARE v_total_etapas INT;
                DECLARE v_total_votadas INT;

                -- 1) VERIFICA SE JÁ EXISTE COMPROVANTE PARA ESTA ETAPA
                SELECT id, chave_autenticacao
                INTO v_comprovante_id, v_chave
                FROM comprovantes
                WHERE eleitor_id = p_eleitor_id
                AND etapa_id = p_etapa_id
                LIMIT 1;

                -- 2) SE NÃO EXISTIR, CRIA
                IF v_comprovante_id IS NULL THEN
                    
                    SET v_chave = UUID();

                    INSERT INTO comprovantes (
                        eleitor_id,
                        etapa_id,
                        nome_votacao,
                        nome_eleitor,
                        cpf_cnpj,
                        ip,
                        data_hora,
                        chave_autenticacao,
                        created_at,
                        updated_at
                    ) VALUES (
                        p_eleitor_id,
                        p_etapa_id,
                        p_nome_votacao,
                        p_nome_eleitor,
                        p_cpf,
                        p_ip,
                        NOW(),
                        v_chave,
                        NOW(),
                        NOW()
                    );

                    SET v_comprovante_id = LAST_INSERT_ID();
                END IF;

                -- 3) VERIFICA SE O ELEITOR CONCLUIU TODAS AS ETAPAS
                SELECT COUNT(*) INTO v_total_etapas
                FROM etapas_candidatos
                WHERE status = 1;

                SELECT COUNT(*) INTO v_total_votadas
                FROM comprovantes
                WHERE eleitor_id = p_eleitor_id;

                -- 4) SE O NÚMERO DE ETAPAS = NÚMERO DE COMPROVANTES → FINALIZOU VOTAÇÃO
                IF v_total_votadas = v_total_etapas THEN
                    UPDATE eleitores
                    SET votou = 1
                    WHERE id = p_eleitor_id;
                END IF;

                -- 5) RETORNO PADRÃO
                SELECT 
                    v_comprovante_id AS comprovante_id,
                    v_chave AS chave_autenticacao;

            END
        ");
    }

    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS finalizar_votacao");
    }
};