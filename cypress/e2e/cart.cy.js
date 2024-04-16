describe('Cart spec', () => {
  beforeEach(() => {
    cy.fixture('path.json').then((data) => {
      cy.visit(data.root + 'login.php')
      cy.get('input').first().type('cust1')
      cy.get('input').eq(1).type('123456')
      cy.get('input').last().click()
      cy.visit(data.root + 'products.php?res_is=51')
    })
  })
  it('Successfully loads products', () => {
    cy.get('div.food-item').should('exist')
  })
  it('Adds product to cart', () => {
    cy.get('.addsToCart').first().click()
    cy.get('#cartItems').find('li').first().should('exist')
  })
  it('Discount is correct', () => {
    cy.get('.product').eq(2).find('p.price').then((base) => {
      const price = parseFloat(base.text().replace('RM', ''))
      
      cy.get('.product').eq(2).find('div').then((disc) => {
        const discount = parseFloat(disc.text().replace('% off', ''))/100

        cy.get('.product').eq(2).find('span').should('have.text', 'RM ' + (price*(1-discount)).toFixed(2))
      })
    })
  })
  it('Check difference between first and second product', () => {
    cy.get('.product').eq(1).find('span').last().then((base) => {
      const price_old = parseFloat(base.text().replace('RM', ''))

      cy.get('.shiftOptions').first().click().then(() => {
        cy.get('.product').eq(1).find('span').last().then((base) => {
          const price_new = parseFloat(base.text().replace('RM', ''))
    
          expect(price_new).to.not.eq(price_old)
        })
      })
    })
  })
  it('Check discount of third product', () => {
    cy.get('.shiftOptions').first().click()
    cy.get('.shiftOptions').first().click()
    cy.get('.product').eq(1).find('p.price').then((base) => {
      const price = parseFloat(base.text().replace('RM', ''))
      
      cy.get('.product').eq(1).find('div').then((disc) => {
        const discount = parseFloat(disc.text().replace('% off', ''))/100

        cy.get('.product').eq(1).find('span').last().should('have.text', 'RM ' + (price*(1-discount)).toFixed(2))
      })
    })
  })
  it('Prices of products in cart is the same as checkout', () => {
    cy.get('.addsToCart').first().click()
    cy.get('.shiftOptions').first().click().click()
    cy.get('.addsToCart').eq(1).click()
    cy.get('#cartTotal').then((total) => {
      const cartTotal = parseFloat(total.text().replace('RM', ''))

      cy.get('#checkout').click()
      cy.wait(1000)
      cy.get('#cartTotal').then((total) => {
        const checkoutTotal = parseFloat(total.text().replace('RM', ''))
        
        expect(checkoutTotal).to.eq(cartTotal)
      })
    })
  })
  it('Stock level updates accordingly after checkout', () => {
    cy.get('.product').first().find('p').eq(1).then((stock) => {
      const stockLevel = parseInt(stock.text().split(" ")[2])
      
      cy.get('.product').first().find('h6').then((name) => {
        const weight = parseInt(name.text().split("(")[1].slice(0, -2))
        
        cy.get('.addsToCart').first().click()
        cy.get('#checkout').click()
        cy.wait(1000)
        cy.get('#confirmOrder').click()

        cy.fixture('path.json').then((data) => {
          cy.visit(data.root + 'products.php?res_is=51')

          cy.get('.product').first().find('p').eq(1).then((stock) => {
            
            expect(parseInt(stock.text().split(" ")[2])).to.eq(stockLevel-weight)
          })
        })
      })
    })
  })
})
