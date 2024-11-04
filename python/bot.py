from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options

import os
import time
import requests

# API
agent = {"User-Agent": 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36'}
api_url = "https://editacodigo.com.br/index/api-whatsapp/xgLNUFtZsAbhZZaxkRh5ofM6Z0YIXwwv" #API PUBLICA

def obter_dados_api():
    api = requests.get(api_url, headers=agent).text
    return api.split(".n.")

def iniciar_driver():

    dir_path = os.getcwd()

    chrome_options = Options()
    chrome_options.add_argument(r"user-data-dir=" + dir_path + "/zap/sessao")
    chrome_options.add_argument("--headless")  # Executa o Chrome em modo headless
    chrome_options.add_argument("--no-sandbox")  # Necessário para ambientes sem sandbox
    chrome_options.add_argument("--disable-dev-shm-usage")  # Necessário em ambientes com pouco espaço de memória compartilhada
    chrome_options.add_argument("--disable-gpu")  # Desabilita a aceleração de GPU
    chrome_options.add_argument("--window-size=1920,1080")  # Tamanho da janela

    driver = webdriver.Chrome(options=chrome_options)
    driver.get('https://web.whatsapp.com/')
    time.sleep(7)

    return driver

def bot(driver):
    try:

        # Obter dados da API
        api_data = obter_dados_api()
        bolinha_notificacao = api_data[3].strip()
        contato_cliente = api_data[4].strip()
        caixa_msg = api_data[5].strip() #//*[@id="main"]/footer/div[1]/div/span/div/div[2]/div[1]/div/div[1]/p
        msg_cliente = api_data[6].strip()

        #PEGAR BOLINHA
        bolinha = driver.find_element(By.CLASS_NAME,bolinha_notificacao)
        bolinha = driver.find_elements(By.CLASS_NAME,bolinha_notificacao)
        clica_bolinha = bolinha[-1]
        acao_bolinha = webdriver.common.action_chains.ActionChains(driver)
        acao_bolinha.move_to_element_with_offset(clica_bolinha,0,-20)
        acao_bolinha.click()
        acao_bolinha.perform()
        acao_bolinha.click()
        acao_bolinha.perform()

        #PEGAR NUMERO
        numero_cliente = driver.find_element(By.XPATH,contato_cliente)
        telefone = numero_cliente.text

        #PEGAR MSG
        todas_msg = driver.find_elements(By.CLASS_NAME,msg_cliente)
        todas_msg_texto = [e.text for e in todas_msg]
        msg = todas_msg_texto[-1]

        #RESPONDENDO CLIENTE
        resposta = requests.get("http://php:80", params={'msg': {msg},'telefone':{telefone}})

        campo_de_texto = driver.find_element(By.XPATH,'//*[@id="main"]/footer/div[1]/div/span/div/div[2]/div[1]/div/div[1]/p')
        campo_de_texto.click()
        botresposta = resposta.text
        campo_de_texto.send_keys(botresposta, Keys.ENTER)

        #fecha o contato
        webdriver.ActionChains(driver).send_keys(Keys.ESCAPE).perform()
        
    except:
        print("Esperando novas mensagens...")

if __name__ == "__main__":

    driver = iniciar_driver()

    while True:
        bot(driver)
        time.sleep(2)
