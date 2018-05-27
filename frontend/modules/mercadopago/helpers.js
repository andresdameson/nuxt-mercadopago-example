import statusMessajes from '~/modules/mercadopago/status-messajes.json'

let getErrorCauseFromResponse = function (response) {
  console.log(response)
  return response.cause.pop()
}

export default {
  getMessage (actionName, response, lang = 'en') {

    let errorCause = getErrorCauseFromResponse(response)
    if (statusMessajes[actionName] === undefined) {
      console.log('Action not found')
      return ''
    }

    let action = statusMessajes[actionName].find(action => {
      let condition = action.code === errorCause.code.toString()
      if (errorCause.description) {
        condition &= action.description === errorCause.description.toString()
      }
      return condition
    })

    if (action === undefined) {
      console.log('Status code not found')
      return response.message
    }

    return action.message[lang]
  }
}