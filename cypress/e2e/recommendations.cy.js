describe('Recommendations spec', () => {
  beforeEach(() => {
    cy.fixture('path.json').then((data) => {
      cy.visit(data.root + 'login.php')
      cy.get('input').first().type('cust1')
      cy.get('input').eq(1).type('123456')
      cy.get('input').last().click()
    })
  })
  it('Can order now', () => {
    cy.get('.addmToCart').first().click()
    cy.fixture('path.json').then((data) => {
      cy.visit(data.root + 'products.php')
      cy.get('#cartItems').find('li').first().should('exist')
    })
  })
})